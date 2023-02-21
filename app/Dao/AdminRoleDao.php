<?php


namespace App\Dao;

use App\Model\AdminRole;

class AdminRoleDao extends AbstractDao
{
    /**
     * @var AdminRole
     */
    public $model;

    public function assignModel() :void
    {
        $this->model = AdminRole::class;
    }

    /**
     * 通过角色ID列表获取菜单ID
     * @param array $ids
     * @return array
     */
    public function getMenuIdsByRoleIds(array $ids): array
    {
        if (empty($ids)) {
            return [];
        }

        return $this->model::query()->whereIn('id', $ids)->with(['menus' => function($query) {
            $query->select('id')->where('status', 1)->orderBy('order');
        }])->get(['id'])->toArray();
    }

    /**
     * 列表
     * @param array|null $params
     * @param string $pageName
     * @return array
     */
    public function roleList(?array $params = null, string $pageName = 'page'): array
    {
        $map = [];
        if (isset($params['name'])) {
            $map[] = ['name','like', '%' . $params['name'] . '%'];
        }

        if (isset($params['status'])) {
            $map[] = ['status','=', $params['name']];
        }
        $pageNo =  $params[$pageName] ?? 1;

        $pageSize = $params['pageSize'] ?? 10;

        $paginate = $this->model::with(['menus' => function ($query) {
            $query->select(['id','title']);
        }])->where($map)->select([
            'id',
            'name',
            'desc',
            'status',
            'created_at',
            'updated_at',
        ])->paginate((int) $pageSize, ['*'], $pageName, (int) $pageNo);

        return $this->setPaginate($paginate);
    }
}