<?php
namespace App\Traits;

namespace App\Traits;

use Hyperf\Contract\LengthAwarePaginatorInterface;
use Hyperf\Database\Model\Builder;

/**
 * Class DaoTrait
 * @package App\Traits
 */
trait DaoTrait
{

    /**
     * @param array|null $params
     * @return array
     */
    public function getList(?array $params = null): array
    {
        return $this->listQuerySet($params)->get()->toArray();
    }

    /**
     * @param array|null $params
     * @param string $pageName
     * @return array
     */
    public function getPageList(?array $params = null, string $pageName = 'page'): array
    {
        $paginate = $this->listQuerySet($params)->paginate(
            $params['pageSize'] ?? 10,['*'],$pageName,$params[$pageName] ?? 1);
        return $this->setPaginate($paginate);
    }

    /**
     * 设置搜索
     * @param array|null $params
     * @return Builder
     */
    public function listQuerySet(?array $params = null): Builder
    {
        // 初始化query对象
        $query = $this->model::query();

        if ($params['select'] ?? false) {
            $query->select($this->filterQueryAttributes($params['select']));
        }

        $query = $this->handleOrder($query, $params);

        return $this->handleSearch($query, $params);
    }

    /**
     * 搜索处理器
     * @param Builder $query
     * @param array $params
     * @return Builder
     */
    public function handleSearch(Builder $query, array $params): Builder
    {
        //var_dump($params);
        //var_dump($this->searchFields);
        return $query;
    }

    /**
     * 过滤查询字段不存在的属性
     * @param array $fields
     * @param bool $removePk
     * @return array
     */
    protected function filterQueryAttributes(array $fields, bool $removePk = false): array
    {
        $model = new $this->model;
        $attrs = $model->getFillable();
        foreach ($fields as $key => $field) {
            if (!in_array(trim($field), $attrs, true) && !str_contains(str_replace('AS', 'as', $field), 'as')) {
                unset($fields[$key]);
            } else {
                $fields[$key] = trim($field);
            }
        }
        if ($removePk && in_array($model->getKeyName(), $fields, true)) {
            unset($fields[array_search($model->getKeyName(), $fields, true)]);
        }
        $model = null;
        return ( count($fields) < 1 ) ? ['*'] : $fields;
    }

    /**
     * 排序处理器
     * @param Builder $query
     * @param array|null $params
     * @return Builder
     */
    public function handleOrder(Builder $query, ?array &$params = null): Builder
    {
        // 对树型数据强行加个排序
        if ($params['orderBy'] ?? false) {
            if (is_array($params['orderBy'])) {
                foreach ($params['orderBy'] as $key => $order) {
                    $query->orderBy($order, $params['orderType'][$key] ?? 'asc');
                }
            } else {
                $query->orderBy($params['orderBy'], $params['orderType'] ?? 'asc');
            }
        }

        return $query;
    }

    /**
     * 设置数据库分页
     * @param LengthAwarePaginatorInterface $paginate
     * @return array
     */
    public function setPaginate(LengthAwarePaginatorInterface $paginate): array
    {
        return [
            'items' => $paginate->items(),
            'pageInfo' => [
                'total' => $paginate->total(),
                'currentPage' => $paginate->currentPage(),
                'totalPage' => $paginate->lastPage()
            ]
        ];
    }

    /**
     * 新增数据
     * @param array $data
     * @return int
     */
    public function save(array $data): int
    {
        $this->filterExecuteAttributes($data, $this->getModel()->incrementing);
        $model = $this->model::create($data);
        return $model->{$model->getKeyName()};
    }

    /**
     * 更新一条数据
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data): bool
    {
        $this->filterExecuteAttributes($data, true);
        if ($model = $this->model::find($id)) {
            return $model->update($data) > 0;
        }
        return false;
    }

    /**
     * 过滤新增或写入不存在的字段
     * @param array $data
     * @param bool $removePk
     */
    public function filterExecuteAttributes(array &$data, bool $removePk = false): void
    {
        $model = new $this->model;
        $attrs = $model->getFillable();
        foreach ($data as $name => $val) {
            if (!in_array($name, $attrs, true)) {
                unset($data[$name]);
            }
            if ($val === '' || $val === null) {
                unset($data[$name]);
            }
        }
        if ($removePk && isset($data[$model->getKeyName()])) {
            unset($data[$model->getKeyName()]);
        }
        // 防止泄露
        $model = null;
    }

    /**
     * 单个或批量删除数据
     * @param array $ids
     * @return bool
     */
    public function delete(array $ids): bool
    {
        $this->model::destroy($ids);
        return true;
    }
}