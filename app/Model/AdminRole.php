<?php

declare(strict_types=1);

namespace App\Model;

use App\System\Model\SystemMenu;
use Hyperf\DbConnection\Model\Model;

/**
 * @property int $id 
 * @property string $name 
 * @property string $desc 
 * @property int $status 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 */
class AdminRole extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'admin_roles';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['id','name','desc','status'];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = ['id' => 'integer', 'name' => 'string', 'desc' => 'string', 'status' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];

    /**
     * 通过中间表获取菜单
     */
    public function menus() : \Hyperf\Database\Model\Relations\BelongsToMany
    {
        return $this->belongsToMany(AdminMenu::class, 'admin_role_menus', 'role_id', 'menu_id');
    }

    /**
     * 通过中间表获取用户
     */
    public function users() : \Hyperf\Database\Model\Relations\BelongsToMany
    {
        return $this->belongsToMany(AdminUser::class, 'system_user_role', 'role_id', 'user_id');
    }
}
