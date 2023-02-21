<?php

declare(strict_types=1);

namespace App\Model;

use Hyperf\DbConnection\Model\Model;

/**
 * @property int $id 
 * @property int $parent_id 
 * @property int $order 
 * @property string $title 
 * @property string $icon 
 * @property int $menu 
 * @property string $uri 
 * @property string $condition 
 * @property string $method 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 */
class AdminMenu extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'admin_menus';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['id', 'parent_id', 'order', 'title', 'menu', 'icon', 'uri', 'condition', 'method', 'status'];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = ['id' => 'integer', 'parent_id' => 'integer', 'order' => 'integer', 'icon' => 'string', 'title' => 'string', 'uri' => 'string', 'condition' => 'string', 'menu' => 'integer', 'method' => 'string', 'status' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];
}
