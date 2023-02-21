<?php


namespace App\Dao;

use App\Model\AdminMenu;

class AdminMenuDao extends AbstractDao
{
    /**
     * @var AdminMenu
     */
    public $model;

    public function assignModel() :void
    {
        $this->model = AdminMenu::class;
    }

    /**
     * 查询的菜单字段
     * @var array
     */
    public array $menuField = [
        'id',
        'parent_id',
        'order',
        'title',
        'icon',
        'menu',
        'uri',
        'condition',
        'method',
        'status',
    ];

    /**
     * 通过菜单ID列表获取菜单数据
     * @param array $ids
     * @return array
     */
    public function getRoutersByIds(array $ids) :array
    {
        return $this->model::query()
            ->select($this->menuField)
            ->whereIn('id', $ids)
            ->where('status', 1)
            ->orderBy( 'order')
            ->get()->toArray();
    }

    /**
     * 通过规则名称 查询 权限名称
     * @param string $code
     * @return string|null
     */
    public function findNameByCondition(string $code): ?string
    {
        return $this->model::query()->where('condition', $code)->value('title');
    }

}