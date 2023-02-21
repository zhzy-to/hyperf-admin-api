<?php

use Hyperf\Utils\ApplicationContext;

if (!function_exists('auth')) {
    /**
     * Auth认证辅助方法
     * @param string|null $guard 守护名称
     */
    function auth(string $guard = 'api'): \HyperfExt\Auth\Contracts\StatefulGuardInterface|\HyperfExt\Auth\Contracts\GuardInterface|\HyperfExt\Auth\Contracts\StatelessGuardInterface
    {
        if (is_null($guard)) {
            $guard = config('auth.default.guard');
        }

        return make(\HyperfExt\Auth\Contracts\AuthManagerInterface::class)->guard($guard);
    }
}

if (! function_exists('container')) {

    /**
     * 获取容器实例
     * @return \Psr\Container\ContainerInterface
     */
    function container(): \Psr\Container\ContainerInterface
    {
        return ApplicationContext::getContainer();
    }

}

if (! function_exists('userID')) {
    /**
     * 获取当前登录用户实例
     */
    function userID()
    {
        $payload = make(\App\Utils\JwtUtil::class)->getPayload()->toArray();

        return $payload['sub'] ?? false;
    }
}


if (! function_exists('toTree')) {
    /**
     * 列表转子父结构
     * @param array $list
     * @param string $field
     * @param string $child
     * @param int $parentId
     * @return array
     */
    function toTree(array $list, $field = 'parent_id', $child = 'child', $parentId = 0): array
    {
        $arr = [];

        foreach ($list as $value) {
            if($value[$field] === $parentId){
                $value[$child] = toTree($list,$field,$child,$value['id']);
                $arr[]     = $value;
            }
        }

        return $arr;
    }
}