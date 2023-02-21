<?php
declare(strict_types=1);

namespace App\Traits;

use App\AppRequest;
use App\AppResponse;
use Psr\Http\Message\ResponseInterface;
use Hyperf\Di\Annotation\Inject;

/**
 *
 * Trait ResponseTrait
 * @package App\Traits
 */
trait ResponseTrait
{
    /**
     * @var AppResponse
     */
    #[Inject]
    protected AppResponse $response;

    /**
     * @var AppRequest
     */
    #[Inject]
    protected AppRequest $request;

    public function success(string $message = 'success', array|object $data = [], int $code = 20000) :ResponseInterface
    {
        return $this->response->success($message, $data, $code);
    }

    public function error(string $message = 'fail', int $code = 50000, array|object $data = []) :ResponseInterface
    {
        return $this->response->error($message, $code ,$data);
    }
}