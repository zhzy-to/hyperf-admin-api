<?php

declare(strict_types=1);

namespace App\Model;

use Hyperf\DbConnection\Model\Model;
use Hyperf\ModelCache\Cacheable;
use HyperfExt\Auth\Authenticatable;
use HyperfExt\Auth\Contracts\AuthenticatableInterface;
use HyperfExt\Jwt\Contracts\JwtSubjectInterface;

/**
 * @property int $id 
 * @property string $username 
 * @property string $password 
 * @property string $name 
 * @property string $avatar 
 * @property int $role_id 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 */
class AdminUser extends Model implements AuthenticatableInterface ,JwtSubjectInterface
{
    use Authenticatable, Cacheable;
    /**
     * The table associated with the model.
     */
    protected $table = 'admin_users';

    /**
     * 隐藏的字段列表
     * @var string[]
     */
    protected $hidden = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['id','username','password','name','avatar','status'];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = ['id' => 'integer', 'username' => 'string', 'password' => 'string', 'name' => 'string', 'avatar' => 'string', 'status'=> 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];

    /**
     * 通过中间表关联角色
     */
    public function roles() : \Hyperf\Database\Model\Relations\BelongsToMany
    {
        return $this->belongsToMany(AdminRole::class, 'admin_user_roles', 'user_id', 'role_id');
    }

    /**
     * 密码加密
     * @param $value
     * @return void
     */
    public function setPasswordAttribute($value) : void
    {
        $this->attributes['password'] = password_hash($value, PASSWORD_BCRYPT, ["cost" => 10]);
    }

    public function getJwtIdentifier()
    {
        return $this->getKey();
    }

    /**
     * JWT自定义载荷
     * @return array
     */
    public function getJwtCustomClaims(): array
    {
        return [
            'guard' => 'api'    // 添加一个自定义载荷保存守护名称，方便后续判断
        ];
    }
}
