<?php
declare(strict_types=1);

namespace App;

use Hyperf\HttpMessage\Stream\SwooleStream;
use Psr\Http\Message\ResponseInterface;
use Hyperf\HttpServer\Response;

class AppResponse extends Response
{
    public function success(string $message = null, array|object $data = [], int $code = 20000): ResponseInterface
    {
        $format = [
            'success' => true,
            'message' => $message,
            'code'    => $code,
            'data'    => &$data,
        ];

        $format = $this->toJson($format);
        return $this->getResponse()
            ->withHeader('Server', 'ZhzyAdmin')
            ->withAddedHeader('content-type', 'application/json; charset=utf-8')
            ->withBody(new SwooleStream($format));
    }

    /**
     * @param string $message
     * @param int $code
     * @param array $data
     * @return ResponseInterface
     */
    public function error(string $message = '', int $code = 500, array $data = []): ResponseInterface
    {
        $format = [
            'success' => false,
            'code'    => $code,
            'message' => $message,
        ];

        if (!empty($data)) {
            $format['data'] = &$data;
        }

        $format = $this->toJson($format);
        return $this->getResponse()
            ->withHeader('Server', 'ZhzyAdmin')
            ->withAddedHeader('content-type', 'application/json; charset=utf-8')
            ->withBody(new SwooleStream($format));
    }

    /**
     * @return ResponseInterface
     */
    public function getResponse(): ResponseInterface
    {
        return parent::getResponse();
    }
}