<?php

declare(strict_types=1);

namespace App\Model;

use Hyperf\DbConnection\Model\Model;

/**
 * @property int $user_id 
 * @property int $role_id 
 */
class AdminUserRole extends Model
{
    public $timestamps = false;
    /**
     * The table associated with the model.
     */
    protected $table = 'admin_user_roles';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = ['user_id' => 'integer', 'role_id' => 'integer'];
}
