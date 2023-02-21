<?php
namespace App\Dao;

use App\Model\AdminRoleMenu;

class AdminRoleMenuDao extends AbstractDao
{
    /**
     * @var AdminRoleMenu
     */
    public $model;

    public function assignModel()
    {
        $this->model = AdminRoleMenu::class;
    }
}