<?php


namespace App\Service;


use App\Dao\MaterialClassificationDao;

/**
 *
 * Class MaterialClassificationService
 * @package App\Service
 */
class MaterialClassificationService extends AbstractService
{
    /**
     * @var MaterialClassificationDao
     */
    public $dao;

    public function __construct(MaterialClassificationDao $dao)
    {
        $this->dao = $dao;
    }

}