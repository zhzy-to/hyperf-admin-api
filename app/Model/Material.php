<?php
declare (strict_types=1);

namespace App\Model;

use Hyperf\DbConnection\Model\Model;

/**
 * 素材
 * Class Material
 * @package App\Model
 */
class Material extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'material';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'class_id', 'path','name'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'class_id' => 'integer','path' => 'string','name' => 'string','created_at' => 'datetime', 'updated_at' => 'datetime'];
}