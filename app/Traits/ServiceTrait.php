<?php
declare(strict_types=1);

namespace App\Traits;

use Hyperf\Contract\LengthAwarePaginatorInterface;

/**
 * Trait ServiceTrait
 * @package App\Traits
 */
trait ServiceTrait
{

    /**
     * 查询列表
     * @param array|null $params
     * @return mixed
     */
    public function getList(?array $params = null): mixed
    {
        // 存在字段查询
        if ($params['select'] ?? null) {
            $params['select'] = explode(',', $params['select']);
        }
        return $this->dao->getList($params);
    }

    /**
     * 带分页查询
     * @param array|null $params
     * @return mixed
     */
    public function getPageList(?array $params = null): mixed
    {
        // 存在字段查询
        if ($params['select'] ?? null) {
            $params['select'] = explode(',', $params['select']);
        }
        return $this->dao->getPageList($params);
    }


    /**
     * 新增数据
     * @param array $data
     * @return int
     */
    public function save(array $data): int
    {
        return $this->dao->save($data);
    }

    /**
     * 更新一条数据
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data): bool
    {
        return $this->dao->update($id, $data);
    }

    /**
     * 单个或批量删除数据
     * @param array $ids
     * @return bool
     */
    public function delete(array $ids): bool
    {
        return !empty($ids) && $this->dao->delete($ids);
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
}