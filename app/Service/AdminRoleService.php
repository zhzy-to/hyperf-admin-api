<?php


namespace App\Service;

use App\Dao\AdminRoleDao;
use Hyperf\DbConnection\Db;

class AdminRoleService extends AbstractService
{
    public $dao;

    public function __construct(AdminRoleDao $dao)
    {
        $this->dao = $dao;
    }

    public function roles(): array
    {
        return $this->dao->getModel()::where('status',1)->select([
            'id',
            'name'
        ])->get()->toArray();
    }

    /**
     * 列表
     * @param array|null $params
     * @param string $pageName
     * @return array
     */
    public function getRoleList(?array $params = null, string $pageName = 'page'): array
    {
        return $this->dao->roleList($params,$pageName);
    }

    /**
     * 设置权限
     * @param array $menuIds
     * @param int $id
     * @return int
     */
    public function setRules(array $menuIds, int $id): bool|int
    {
        $role = $this->dao->getModel()::find($id);

        Db::beginTransaction();
        try {
            Db::table('admin_role_menus')->where('role_id',$id)->delete();
            $role->menus()->sync(array_unique($menuIds), false);

            Db::commit();
        } catch(\Throwable $e){

            make(\Hyperf\Framework\Logger\StdoutLogger::class)->warning($e->getMessage());
            Db::rollBack();
            return false;
        }

        return true;
    }
}