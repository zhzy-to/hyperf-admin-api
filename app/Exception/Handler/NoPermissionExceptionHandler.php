<?php
declare(strict_types=1);

namespace App\Exception\Handler;

use Hyperf\ExceptionHandler\ExceptionHandler;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Hyperf\Utils\Codec\Json;
use Psr\Http\Message\ResponseInterface;
use Throwable;
use App\Exception\NoPermissionException;

class NoPermissionExceptionHandler extends ExceptionHandler
{

    public function handle(Throwable $throwable, ResponseInterface $response)
    {
        // 阻止异常冒泡
        $this->stopPropagation();

        $format = [
            'success' => false,
            'message' => $throwable->getMessage(),
            'code'    => 403,
        ];

        return $response->withHeader('Server', 'ZhzyAdmin')
            ->withAddedHeader('content-type', 'application/json; charset=utf-8')
            ->withStatus(403)->withBody(new SwooleStream(Json::encode($format)));

//        logger('Exception log')->debug($throwable->getMessage());
    }

    public function isValid(Throwable $throwable): bool
    {
        return $throwable instanceof NoPermissionException;
    }
}