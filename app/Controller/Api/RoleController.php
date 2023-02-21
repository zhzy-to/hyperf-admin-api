<?php
declare(strict_types=1);

namespace App\Controller\Api;

use App\Request\RoleRequest;
use Hyperf\Di\Annotation\Inject;
use App\Service\AdminRoleService;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\DeleteMapping;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\PostMapping;
use Hyperf\HttpServer\Annotation\PutMapping;
use Psr\Http\Message\ResponseInterface;


/**
 * 角色
 * Class RoleController
 * @package App\Controller\Api
 */
#[Controller(prefix: "api/role")]
class RoleController extends MainController
{
    /**
     * @var AdminRoleService
     */
    #[Inject]
    protected AdminRoleService $adminRoleService;

    /**
     * 角色集合
     * @return ResponseInterface
     */
    #[GetMapping("roles")]
    public function roles(): ResponseInterface
    {
        $data = $this->adminRoleService->roles();
        return $this->success('success', $data);
    }

    /**
     * 角色列表
     * @return ResponseInterface
     */
    #[GetMapping("roleList")]
    public function roleList(): ResponseInterface
    {
        return $this->success(
            'success',
            $this->adminRoleService->getRoleList($this->request->all())
        );
    }

    /**
     * 新增
     * @param RoleRequest $request
     * @return ResponseInterface
     */
    #[PostMapping("create")]
    public function create(RoleRequest $request): ResponseInterface
    {
        //php bin/hyperf.php gen:request RoleRequest
        $data = $request->inputs(['name','desc','status']);

        if ($this->adminRoleService->save($data) >= 1) {
            return $this->success();
        }

        return $this->error();
    }

    /**
     * 更新
     * @param int $id
     * @param RoleRequest $request
     * @return ResponseInterface
     */
    #[PutMapping("update/{id}")]
    public function update(int $id,RoleRequest $request): ResponseInterface
    {
        $data = $request->inputs(['name','desc','status']);

        if ($this->adminRoleService->update($id,$data)) {
            return $this->success();
        }
        return $this->error();
    }

    /**
     * 删除
     * @param int $id
     * @return ResponseInterface
     */
    #[DeleteMapping("delete/{id}")]
    public function delete(int $id): ResponseInterface
    {
        $this->adminRoleService->delete((array) $id);
        return $this->success();
    }

    /**
     * 设置权限
     * @param int $id
     * @return ResponseInterface
     */
    #[PostMapping("setRules/{id}")]
    public function setRules(int $id): ResponseInterface
    {
        $data = $this->request->input('rule_ids');
        $this->adminRoleService->setRules($data,$id);
        return $this->success();
    }
}