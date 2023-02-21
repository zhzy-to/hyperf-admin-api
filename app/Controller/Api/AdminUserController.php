<?php
declare(strict_types=1);

namespace App\Controller\Api;

use App\Middleware\Auth\RefreshTokenMiddleware;
use App\Service\AdminUserService;
use Hyperf\HttpServer\Annotation\AutoController;
use Psr\Http\Message\ResponseInterface;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Middlewares;


/**
 * 后台用户
 * Class AdminUserController
 * @package App\Controller\Api
 */
#[AutoController]
class AdminUserController extends MainController
{

    #[Inject]
    protected AdminUserService $adminUserService;

    public function login(): ResponseInterface
    {
        $credentials = $this->request->inputs(['username', 'password']);

        if (!$token = auth('api')->attempt([
            'username' => (string) $credentials['username'],
            'password' => (string) $credentials['password'],
        ])) {
            return $this->error('auth fail');
        }

        return $this->success('success',[
            'token' => $token
        ]);
    }

    /**
     * 账户是否注册
     * @return ResponseInterface
     */
    public function accountExist() :ResponseInterface
    {
        $name = $this->request->query('name','');

        return $this->success('success',[
            'exist' => $this->adminUserService->existUser($name)
        ]);
    }

    /**
     * 获取用户信息
     * @return ResponseInterface
     */
    #[Middlewares(RefreshTokenMiddleware::class)]
    public function getInfo() :ResponseInterface
    {
        $data = $this->adminUserService->getInfo();

        return $this->success('success',$data);
    }

    /**
     * 退出登录
     * @return ResponseInterface
     */
    public function logout(): ResponseInterface
    {
        auth('api')->logout();
        return $this->success('Successfully logged out');
    }

    /**
     * 手动签发
     * @return ResponseInterface
     */
    public function manualLogin() :ResponseInterface
    {
        $token = $this->adminUserService->toIssued(2);
        return $this->success('success',[
            'token' => $token
        ]);

    }
}