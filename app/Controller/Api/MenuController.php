<?php
declare(strict_types=1);

namespace App\Controller\Api;

use App\Request\MenuRequest;
use App\Service\AdminMenuService;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\DeleteMapping;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\PostMapping;
use Hyperf\HttpServer\Annotation\PutMapping;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\Validation\Contract\ValidatorFactoryInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * 菜单权限
 * Class MenuController
 * @package App\Controller\Api
 */
#[Controller(prefix: "api/menu")]
class MenuController extends MainController
{
    /**
     * @var AdminMenuService
     */
    #[Inject]
    protected AdminMenuService $menuService;


    /**
     * @var ValidatorFactoryInterface
     */
    #[Inject]
    protected ValidatorFactoryInterface $validationFactory;

    /**
     * 菜单列表
     * @return ResponseInterface
     */
    #[GetMapping("menuList")]
    public function menuList(): ResponseInterface
    {
        return $this->success(
            'success',
            $this->menuService->treeList($this->request->all())
        );
    }

    /**
     * 新增菜单
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    #[PostMapping("create")]
    public function create(RequestInterface $request): ResponseInterface
    {
        $validator = $this->validationFactory->make(
            $request->all(),
            [
                'parent_id' => 'required',
                'menu' => 'boolean',
                'title' => 'required',
            ],
            [
                'parent_id.required' => 'username is required',
                'menu.boolean' => 'menu is boolean',
                'title.required' => 'password is required',
            ]
        );

        if ($validator->fails()){
            $errorMessage = $validator->errors()->first();
            return $this->error($errorMessage);
        }

        $data = $request->inputs([
            'parent_id','order','title','icon',
            'menu','uri','condition','method','status'
        ]);

        if ($this->menuService->save($data) >= 1) {
            return $this->success('success');
        }

        return $this->error('fail');
    }

    /**
     * 更新
     * @param int $id
     * @param MenuRequest $request
     * @return ResponseInterface
     */
    #[PutMapping("update/{id}")]
    public function update(int $id,MenuRequest $request): ResponseInterface
    {
        // php bin/hyperf.php gen:request MenuRequest

        $data = $request->inputs([
            'parent_id','order','title','icon',
            'menu','uri','condition','method','status'
        ]);

        if ($this->menuService->update($id,$data)) {
            return $this->success();
        }

        return $this->error();
    }

    /**
     * 更新状态
     * @param int $id
     * @return ResponseInterface
     */
    #[PostMapping("updateStatus/{id}")]
    public function updateStatus(int $id): ResponseInterface
    {
        $data = $this->request->inputs(['status']);

        if ($this->menuService->update($id,$data)) {
            return $this->success();
        }

        return $this->error();
    }

    /**
     * @param int $id
     * @return ResponseInterface
     */
    #[DeleteMapping("delete/{id}")]
    public function delete(int $id): ResponseInterface
    {
        $this->menuService->delete((array) $id);
        return $this->success();
    }
}