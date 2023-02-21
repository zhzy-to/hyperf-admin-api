<?php
declare(strict_types=1);

namespace App\Controller\Api;

use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\DeleteMapping;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\PostMapping;
use Hyperf\HttpServer\Annotation\PutMapping;
use Hyperf\HttpServer\Annotation\Middlewares;
use Psr\Http\Message\ResponseInterface;
use App\Service\AdminUserService;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Validation\Contract\ValidatorFactoryInterface;
use Hyperf\HttpServer\Contract\RequestInterface;


/**
 * 管理者
 * Class ManagerController
 * @package App\Controller\Api
 */
#[Controller(prefix: "api/manager")]
class ManagerController extends MainController
{
    /**
     * @var AdminUserService
     */
    #[Inject]
    protected AdminUserService $adminUserService;


    /**
     * @var ValidatorFactoryInterface
     */
    #[Inject]
    protected ValidatorFactoryInterface $validationFactory;

    /**
     * 管理列表
     * @return ResponseInterface
     */
    #[GetMapping("managerList")]
    public function managerList() :ResponseInterface
    {
        $data = $this->adminUserService->getManagerList($this->request->all());

        return $this->success('success', $data);
    }

    /**
     * 创建管理员
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    #[PostMapping("create")]
    public function create(RequestInterface $request): ResponseInterface
    {
        $validator = $this->validationFactory->make(
            $request->all(),
            [
                'username' => 'required',
                'password' => 'required',
                'name' => 'required',
            ],
            [
                'username.required' => 'username is required',
                'password.required' => 'password is required',
                'name.required' => 'name is required',
            ]
        );

        if ($validator->fails()){
            $errorMessage = $validator->errors()->first();
            return $this->error($errorMessage);
        }

        $data = $request->inputs(['username','password','name','avatar']);
        $roleIds = (array) $request->input('role_ids',0);

        if ($this->adminUserService->existUser($request->input('username'))) {
            return $this->error('username exists');
        }

        if ($this->adminUserService->create($data,$roleIds)) {
            return $this->success('success');
        }

        return $this->error('create fail');
    }

    /**
     * 更新
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    #[PutMapping("update")]
    public function update(RequestInterface $request): ResponseInterface
    {
        $data = $request->inputs(['username','password','name','avatar','roleIds','status']);

        $id = (int) $request->input('id',0);

        if ($this->adminUserService->update($id,$data)) {
            return $this->success('success');
        }

        return $this->error('update error');
    }
}