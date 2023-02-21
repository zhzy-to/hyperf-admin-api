<?php

namespace App\Service;

use App\Dao\MaterialDao;

class MaterialService extends AbstractService
{
    /**
     * @var MaterialDao
     */
    public $dao;

    public function __construct(MaterialDao $dao)
    {
        $this->dao = $dao;
    }

    /**
     * 通过classId获取素材列表
     * @param int $class_id
     * @param array|null $params
     * @return array
     */
    public function getListByClass(int $class_id, ?array $params = null): array
    {
        $query = $this->dao->model::query();

        $paginate = $query->where('class_id',$class_id)->paginate(
            $params['pageSize'] ?? 10,['*'],'page',$params['page'] ?? 1);

        return $this->dao->setPaginate($paginate);
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function getById(int $id)
    {
        return $this->dao->model::findOrFail($id);
    }
}