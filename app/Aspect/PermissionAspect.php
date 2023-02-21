<?php
declare(strict_types=1);

namespace App\Aspect;


use App\Annotation\Permission;
use App\AppRequest;
use App\Exception\NoPermissionException;
use App\Service\AdminUserService;
use Hyperf\Di\Aop\AbstractAspect;
use Hyperf\Di\Aop\ProceedingJoinPoint;
use Hyperf\Di\Annotation\Aspect;
use Hyperf\Di\Annotation\Inject;


/**
 * Class PermissionAspect
 * @package App\Aspect
 */
#[Aspect]
class PermissionAspect extends AbstractAspect
{

    public $annotations = [
        Permission::class
    ];

    /**
     * @var AdminUserService
     */
    #[Inject]
    protected AdminUserService $service;

    /**
     * @var AppRequest
     */
    #[Inject]
    protected AppRequest $request;

    public function process(ProceedingJoinPoint $proceedingJoinPoint)
    {
        /** @var Permission $permission */
        if (isset($proceedingJoinPoint->getAnnotationMetadata()->method[Permission::class])) {
            $permission = $proceedingJoinPoint->getAnnotationMetadata()->method[Permission::class];
        }

        // 注解权限为空，则放行
        if (empty($permission->code)) {
            return $proceedingJoinPoint->process();
        }

        $this->checkPermission($permission->code, $permission->where);

        return $proceedingJoinPoint->process();
    }

    /**
     * @param string $codeString
     * @param string $where
     * @return bool
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    protected function checkPermission(string $codeString, string $where): bool
    {
        $where = strtoupper($where);
        $codes = $this->service->getInfo()['ruleNames'];

        if ($where === 'OR') {
            foreach (explode(',', $codeString) as $code) {
                if (in_array(trim($code), $codes, true)) {
                    return true;
                }
            }
            throw new NoPermissionException(
                 'system.no_permission -> [ ' . $this->request->getPathInfo() . ' ]'
            );
        }

        if ($where === 'AND') {
            foreach (explode(',', $codeString) as $code) {
                $code = trim($code);
                if (!in_array($code, $codes, true)) {
                    $service = container()->get(\App\Service\AdminMenuService::class);

                    throw new NoPermissionException(
                        'system.no_permission -> [ ' . $service->findNameByCondition($code) . ' ]'
                    );
                }
            }
        }

        return true;
    }
}