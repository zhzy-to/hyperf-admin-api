<?php
declare(strict_types=1);

namespace App\Dao;

use Hyperf\Context\Context;
use Hyperf\DbConnection\Model\Model;
use App\Traits\DaoTrait;

/**
 * 数据访问层
 * Class AbstractDao
 * @package App\Dao
 */
abstract class AbstractDao
{

    /**
     * utils
     */
    use DaoTrait;

    /**
     * @var
     */
    public $model;

    public array $searchFields = [];

    abstract public function assignModel();

    public function __construct()
    {
        $this->assignModel();
    }

    public function getModel() :Model
    {
        return new $this->model;
    }

    /**
     * 把数据设置为类属性
     * @param array $data
     */
    public static function setAttributes(array $data): void
    {
        Context::set('attributes', $data);
    }

    /**
     * 获取数据
     * @return array
     */
    public function getAttributes(): array
    {
        return Context::get('attributes', []);
    }
}