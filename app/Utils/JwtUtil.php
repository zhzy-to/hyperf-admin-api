<?php
declare(strict_types=1);

namespace App\Utils;

use HyperfExt\Jwt\Contracts\JwtFactoryInterface;
use HyperfExt\Jwt\Contracts\ManagerInterface;

class JwtUtil
{
    /**
     * 提供了对 JWT 编解码、刷新和失活的能力。
     *
     * @var \HyperfExt\Jwt\Contracts\ManagerInterface
     */
    protected ManagerInterface $manager;

    /**
     * 提供了从请求解析 JWT 及对 JWT 进行一系列相关操作的能力。
     *
     * @var \HyperfExt\Jwt\Jwt
     */
    protected \HyperfExt\Jwt\Jwt $jwt;

    public function __construct(
        ManagerInterface $manager,
        JwtFactoryInterface $jwtFactory
    ) {
        $this->manager = $manager;
        $this->jwt = $jwtFactory->make();
    }

    /**
     * @param \HyperfExt\Jwt\Contracts\JwtSubjectInterface $user
     * @return string
     */
    public function issued(\HyperfExt\Jwt\Contracts\JwtSubjectInterface $user): string
    {
        return $this->jwt->fromUser($user);
    }

    /**
     * @return \HyperfExt\Jwt\Payload
     * @throws \HyperfExt\Jwt\Exceptions\JwtException
     */
    public function getPayload(): \HyperfExt\Jwt\Payload
    {
        return $this->jwt->parseToken()->getPayload();
    }
}