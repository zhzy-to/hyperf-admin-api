<?php

declare(strict_types=1);

namespace App\Model;

use Hyperf\DbConnection\Model\Model;

/**
 * @property int $role_id 
 * @property int $menu_id 
 */
class AdminRoleMenu extends Model
{
    public $timestamps = false;
    /**
     * The table associated with the model.
     */
    protected $table = 'admin_role_menus';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = ['role_id' => 'integer', 'menu_id' => 'integer'];
}
