<?php
namespace App\Dao;

use App\Model\Material;

class MaterialDao extends AbstractDao
{

    /**
     * @var
     */
    public $model;

    public function assignModel()
    {
        $this->model = Material::class;
    }
}