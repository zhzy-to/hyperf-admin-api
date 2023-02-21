<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Annotation\Permission;
use App\Middleware\Auth\RefreshTokenMiddleware;
use App\Service\MaterialClassificationService;
use App\Service\MaterialService;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\Middlewares;
use Hyperf\HttpServer\Contract\RequestInterface;
use League\Flysystem\FilesystemException;
use Psr\Http\Message\ResponseInterface;
use Hyperf\HttpServer\Annotation\DeleteMapping;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\PostMapping;
use Hyperf\HttpServer\Annotation\PutMapping;

/**
 * 素材
 * Class MaterialClassificationController
 * @package App\Controller\Api
 */
#[Controller(prefix: "api/material"),Middlewares(RefreshTokenMiddleware::class)]
class MaterialController extends MainController
{
    #[Inject]
    protected MaterialClassificationService $materialClassificationService;

    #[Inject]
    protected MaterialService $materialService;

    /**
     * 分类列表
     * @return ResponseInterface
     */
    #[GetMapping("classificationList"), Permission("material:class:list","AND")]
    public function classificationList(): ResponseInterface
    {
        $data = $this->materialClassificationService->getPageList($this->request->all());

        return $this->success('success',$data);
    }

    /**
     * 创建分类
     * @return ResponseInterface
     */
    #[PostMapping("createClassification")]
    public function createClassification(): ResponseInterface
    {
        return $this->success('success',
            ['id' => $this->materialClassificationService->save($this->request->all())]
        );
    }

    /**
     * 更新分类
     * @param int $id
     * @return ResponseInterface
     */
    #[PutMapping("updateClassification/{id}")]
    public function updateClassification(int $id): ResponseInterface
    {
        return $this->materialClassificationService->update($id,$this->request->all()) ? $this->success('success') : $this->error('update error');
    }

    /**
     * 删除分类
     * @return ResponseInterface
     */
    #[DeleteMapping("deleteClassification")]
    public function deleteClassification(): ResponseInterface
    {
        $this->materialClassificationService->delete((array) $this->request->input('ids',[]));
        return $this->success('success');
    }

    /**
     * 获取分类下素材列表
     * @param int $classId
     * @return ResponseInterface
     */
    #[GetMapping("materialList/{classId}")]
    public function materialList(int $classId): ResponseInterface
    {
        $data = $this->materialService->getListByClass($classId,$this->request->all());
        $data['items'] = array_map(static function ($value){
            $value['path'] = 'http://' . env('QINBIU_DOMAIN') . '/' . $value['path'];
            return $value;
        },$data['items']);

        return $this->success('success',$data);
    }

    /**
     * 更新素材图片
     * @param int $id
     * @return ResponseInterface
     */
    #[PutMapping("updateMaterial/{id}")]
    public function updateMaterial(int $id)
    {
       return $this->materialService->update($id,$this->request->all()) ? $this->success('success') : $this->error('update error');
    }

    /**
     * 删除记录
     * 删除远程文件
     * @param int $id
     * @return ResponseInterface
     */
    #[DeleteMapping("deleteMaterial")]
    public function deleteMaterial(): ResponseInterface
    {
        $id =(int) $this->request->input('id',0);
        $model = $this->materialService->getById($id);
        if (!$model) {
            return $this->error('data is not exist');
        }

        // 转数组
        $ids = (array) $id;

        $wg = new \Hyperf\Utils\WaitGroup();
        $wg->add(2);

        co(function () use ($wg,$ids) {
            $this->materialService->delete($ids);
            $wg->done();
        });

        co(static function () use($model,$wg) {
            $factory = make(\Hyperf\Filesystem\FilesystemFactory::class);
            $qn = $factory->get('qiniu');
            try {
                $qn->delete($model->path);
            } catch (\Exception | FilesystemException $e) {

            }
            $wg->done();
        });

        $wg->wait();

        return $this->success('success');
    }

    /**
     * 上传文件到本地
     * @param \League\Flysystem\Filesystem $filesystem
     * @return ResponseInterface
     * @throws \League\Flysystem\FilesystemException
     */
    #[PostMapping("uploadToLocal")]
    public function uploadToLocal(\League\Flysystem\Filesystem $filesystem): ResponseInterface
    {
        $file = $this->request->file('upload');
        if (!$file) {
            return $this->error('error: file on null');
        }
        $stream = fopen($file->getRealPath(), 'rb+');
        $filesystem->writeStream(
            'uploads/'.$file->getClientFilename(),
            $stream
        );
        fclose($stream);
        return $this->success('success');
    }

    /**
     * 上传文件到七牛云
     * @param \Hyperf\Filesystem\FilesystemFactory $factory
     * @return ResponseInterface
     * @throws \League\Flysystem\FilesystemException
     */
    #[PostMapping("uploadToQn")]
    public function uploadToQn(\Hyperf\Filesystem\FilesystemFactory $factory): ResponseInterface
    {
        $file = $this->request->file('upload');
        if (!$file) {
            return $this->error('error: file on null');
        }
        $qn = $factory->get('qiniu');

        $stream = fopen($file->getRealPath(), 'rb+');
        $location = 'admin/' . date("Ymd"). '/' .$file->getClientFilename();
        $qn->writeStream(
            $location,
            $stream
        );
        fclose($stream);

        return $this->success('success',[
            'domian' => env('QINBIU_DOMAIN'),
            'path' => $location,
        ]);
    }

    /**
     * 上传文件到七牛云
     * 保存记录
     * @param \Hyperf\Filesystem\FilesystemFactory $factory
     * @return ResponseInterface
     * @throws FilesystemException
     */
    #[PostMapping("uploadAndSave")]
    public function uploadAndSave(\Hyperf\Filesystem\FilesystemFactory $factory): ResponseInterface
    {
        $file = $this->request->file('upload');
        if (!$file) {
            return $this->error('error: file on null');
        }
        $qn = $factory->get('qiniu');

        $stream = fopen($file->getRealPath(), 'rb+');
        $location = 'admin/' . date("Ymd"). '/' .$file->getClientFilename();
        $qn->writeStream(
            $location,
            $stream
        );
        fclose($stream);


        $this->materialService->save([
            'name' => $location,
            'path' => $location,
            'class_id' => $this->request->input('class_id') ?? 0
        ]);

        return $this->success('success',[
            'domian' => env('QINBIU_DOMAIN'),
            'path' => $location,
        ]);
    }
}