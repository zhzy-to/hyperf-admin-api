<?php

namespace App\Dao;

use App\Model\MaterialClassification;

class MaterialClassificationDao extends AbstractDao
{
    /**
     * @var MaterialClassification
     */
    public $model;

    /**
     * 搜索值
     * @var array|string[]
     */
    public array $searchFields = [
        'id',
        'name',
    ];

    public function assignModel() :void
    {
        $this->model = MaterialClassification::class;
    }
}