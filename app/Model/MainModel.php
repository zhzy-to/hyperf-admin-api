<?php
declare(strict_types=1);

namespace App\Model;

use Hyperf\DbConnection\Model\Model;
use Hyperf\ModelCache\Cacheable;

class MainModel extends Model
{
    use Cacheable;

    /**
     * 隐藏的字段列表
     * @var string[]
     */
    protected $hidden = ['deleted_at'];
}