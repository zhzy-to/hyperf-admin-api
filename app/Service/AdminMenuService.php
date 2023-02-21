<?php


namespace App\Service;


use App\Dao\AdminMenuDao;

class AdminMenuService extends AbstractService
{
    /**
     * @var AdminMenuDao
     */
    public $dao;

    public function __construct(AdminMenuDao $dao)
    {
        $this->dao = $dao;
    }

    public function findNameByCondition(string $code): string
    {
        if ($code === '') {
            return 'undefined_menu';
        }

        $name = $this->dao->findNameByCondition($code);
        return $name ?? 'undefined_menu';
    }

    /**
     * 获取权限菜单数据 并格式化
     * @param array|null $params
     * @param string $pageName
     * @return array
     */
    public function treeList(?array $params = null, string $pageName = 'page'): array
    {
        $model = $this->dao->getModel();

        $pageNo =  $params[$pageName] ?? 1;
        $pageSize = $params['pageSize'] ?? 1000;

        $paginate = $model::query()->orderBy('id', 'asc')
            ->orderBy('order','asc')
            ->paginate((int) $pageSize, ['*'], $pageName, (int) $pageNo);

        $data = $this->setPaginate($paginate);

        $data['items'] = toTree($data['items']);

        // 只查询菜单
        $menus = $model::query()->where('menu',1)
            ->orderBy('id', 'asc')
            ->orderBy('order','asc')
            ->get()
            ->toArray();

        $data['menus'] = toTree($menus);

        return $data;
    }
}