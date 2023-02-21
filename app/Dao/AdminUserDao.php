<?php
declare(strict_types=1);

namespace App\Dao;

use App\Model\AdminUser;

class AdminUserDao extends AbstractDao
{

    /**
     * @var AdminUser
     */
    public $model;

    public function assignModel() :void
    {
        // TODO: Implement assignModel() method.
        $this->model = AdminUser::class;
    }

    public function existByName(string $name): bool
    {
        //return $this->model::query()->where('username',$name)->first();
        return $this->model::query()->where('username',$name)->exists();
    }

    /**
     * 管理人员列表
     * @param array|null $params
     * @param string $pageName
     * @return array
     */
    public function managerList(?array $params = null, string $pageName = 'page'): array
    {
        $map = [];
        if (isset($params['username'])) {
            $map[] = ['username','=',(string) $params['username']];
        }
        if (isset($params['name'])) {
            $map[] = ['name','like', '%' . $params['name'] . '%'];
        }
        $pageNo =  $params[$pageName] ?? 1;

        $pageSize = $params['pageSize'] ?? 10;

        $paginate = $this->model::with('roles')->where($map)->select([
            'id',
            'username',
            'name',
            'avatar',
            'status',
            'created_at',
            'updated_at',
        ])->paginate((int) $pageSize, ['*'], $pageName, (int) $pageNo);

        return $this->setPaginate($paginate);
    }
}