<?php
declare(strict_types=1);

namespace App\Service;
use App\Dao\AdminUserDao;
use App\Exception\SystemException;
use App\Utils\JwtUtil;
use Hyperf\Cache\Annotation\Cacheable;
use Hyperf\DbConnection\Db;

class AdminUserService extends AbstractService
{
    /**
     * @var AdminUserDao
     */
    public $dao;

    /**
     * @var AdminRoleService
     */
    protected AdminRoleService $adminRoleService;

    /**
     * @var AdminMenuService
     */
    protected AdminMenuService $adminMenuService;

    protected JwtUtil $jwtUtil;

    public function __construct(
        AdminUserDao $dao ,
        AdminRoleService $adminRoleService,
        AdminMenuService $adminMenuService,
        JwtUtil $jwtUtil,
    )
    {
        $this->dao = $dao;
        $this->adminRoleService = $adminRoleService;
        $this->adminMenuService = $adminMenuService;
        $this->jwtUtil = $jwtUtil;
    }

    /**
     * 用户是否存在
     * @param string $name
     * @return bool
     */
    public function existUser(string $name): bool
    {
       return $this->dao->existByName($name) ?? false;
    }

    /**
     * 获取用户信息 与 菜单信息
     * @return array
     */
    public function getInfo() :array
    {
        if ($userId = userID()) {
            return $this->getCacheInfo((int) $userId);
        }
        throw new SystemException("get user info error",500);

    }

    /**
     * @param int $userId
     * @return array
     */
    #[Cacheable(prefix: "userInfo", ttl: 9000, value: "userId_#{userId}",listener:"user-update")]
    protected function getCacheInfo(int $userId): array
    {
        // 获取User模型实例
        $user = $this->dao->getModel()->find($userId);
        // 获取角色id
        $roleIds = $user->roles()->pluck('id')->toArray();
        // 获取路由id
        $menuIds = $this->filterMenuIds($this->adminRoleService->dao->getMenuIdsByRoleIds($roleIds));

        $data['user'] = $user->toArray();
        $menus = $this->adminMenuService->dao->getRoutersByIds($menuIds);

        $data['routers'] = $this->formatRouters($this->getMenuOrPermission($menus,1));

        $data['ruleNames'] = $this->getMenuOrPermission($menus,0);

        return $data;
    }

    /**
     * 获取菜单 或者 路由权限
     * @param array $menus
     * @param int $type 1 菜单 0 权限
     * @return array
     */
    protected function getMenuOrPermission(array $menus, int $type = 1) :array
    {
        $data = [];
        foreach ($menus as $menu) {
            if ($menu['menu'] === $type) {
                // 权限只取名称
                if ($type === 0) {
                    // 权限别名与请求方式
                    //$data[] = $menu['condition'] . ',' . $menu['method'];
                    $data[] = $menu['condition'];
                } else {
                    $data[] = $menu;
                }

            }
        }

        return $data;
    }

    /**
     * 格式化路由
     * @param array $routers
     * @param int $parentId
     * @param string $id
     * @param string $parentField
     * @param string $child
     * @return array
     */
    protected function formatRouters(array $routers, int $parentId = 0, string $id = 'id', string $parentField = 'parent_id', string $child='child') :array
    {
        if (empty($routers)) {
            return [];
        }

        $tree = [];
        foreach ($routers as $router) {

            if ($router[$parentField] === $parentId) {
                $children = $this->formatRouters($routers, $router[$id], $id, $parentField, $child);
                if (!empty($children)) {
                    $router[$child] = $children;
                }
                array_push($tree, $router);
            }
        }

        unset($routers);
        return $tree;
    }

    /**
     * 过滤通过角色查询出来的菜单id列表，并去重
     * @param array $roleData
     * @return array
     */
    protected function filterMenuIds(array $roleData): array
    {
        $ids = [];
        foreach ($roleData as $val) {
            foreach ($val['menus'] as $menu) {
                $ids[] = $menu['id'];
            }
        }
        return array_unique($ids);
    }

    /**
     * 签发Token
     * @param int $userId
     * @return string|null
     */
    public function toIssued(int $userId): ?string
    {
        $user = $this->dao->getModel()->find($userId);

        if ($user instanceof \HyperfExt\Jwt\Contracts\JwtSubjectInterface) {
            return $this->jwtUtil->issued($user);
        }

        return null;
    }

    /**
     * 管理列表
     * @param array|null $params
     * @param string $pageName
     * @return array
     */
    public function getManagerList(?array $params = null, string $pageName = 'page'): array
    {
        return $this->dao->managerList($params,$pageName);
    }

    /**
     * 新增管理员
     * @param array $data
     * @param array $roleIds
     * @return bool
     */
    public function create(array $data,array $roleIds): bool
    {
        Db::beginTransaction();
        try{

            $lastId = $this->dao->save($data);
            // 中间表新增数据
            $user_roles = [];
            foreach ($roleIds as $roleId) {
                $user_roles[] = [
                    'user_id' => $lastId,
                    'role_id' => $roleId,
                ];
            }

            if ($user_roles) {
                Db::table('admin_user_roles')->insert($user_roles);
            }
            Db::commit();
        } catch(\Throwable $e){

            make(\Hyperf\Framework\Logger\StdoutLogger::class)->warning($e->getMessage());

            Db::rollBack();
            return false;
        }

        return true;
    }

    /**
     * 更新
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data): bool
    {
        $role_ids = $data['roleIds'] ?? [];
        $this->dao->filterExecuteAttributes($data, true);
        // 过滤
        $data = array_filter($data, static function ($item) {

            if (is_string($item) && empty($item)) {
                return false;
            }
            if (is_null($item)) {
                return false;
            }

            return true;
        });

        $user = $this->dao->getModel()->find($id);
        $result = $user->update($data);
        if ($user && $result) {
            !empty($role_ids) && $user->roles()->sync($role_ids);
            return true;
        }
        return false;
    }
}