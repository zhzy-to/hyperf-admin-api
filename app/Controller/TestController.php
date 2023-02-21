<?php
declare(strict_types=1);

namespace App\Controller;

use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\HttpServer\Annotation\GetMapping;

/**
 * Class TestController
 * @package App\Controller
 */
#[AutoController()]
class TestController
{
    #[GetMapping("index")]
    public function index(): string
    {
        return 'hello test';
    }
}