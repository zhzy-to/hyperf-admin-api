<?php
declare(strict_types=1);

namespace App\Controller\Api;

use App\Middleware\Auth\RefreshTokenMiddleware;
use Hyperf\HttpServer\Annotation\AutoController;
use Psr\Http\Message\ResponseInterface;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Middlewares;

/**
 * Class StatisticController
 * @package App\Controller\Api
 */
#[AutoController]
#[Middlewares(RefreshTokenMiddleware::class)]
class StatisticController extends MainController
{
    /**
     * 订单统计
     * @return ResponseInterface
     */
    public function order(): ResponseInterface
    {
        return $this->success('success',[
            'panels' => [
                [
                    'title' => '支付订单',
                    'value' => 51,
                    'unit' => '年',
                    'unitColor' => 'success',
                    'subTitle' => '总支付订单',
                    'subValue' => 51,
                    'subUnit' => '',
                ],
                [
                    'title' => '订单量',
                    'value' => 555,
                    'unit' => '周',
                    'unitColor' => 'danger',
                    'subTitle' => '转化率',
                    'subValue' => "60%",
                    'subUnit' => '',
                ],
                [
                    'title' => '销售额',
                    'value' => 3.74,
                    'unit' => '年',
                    'unitColor' => '',
                    'subTitle' => '总销售额',
                    'subValue' => 3.74,
                    'subUnit' => '',
                ],
                [
                    'title' => '新增用户',
                    'value' => 3.74,
                    'unit' => '年',
                    'unitColor' => 'warning',
                    'subTitle' => '总用户',
                    'subValue' => 3.74,
                    'subUnit' => '人',
                ],
            ],
        ]);
    }

    /**
     * 图表统计
     * @return ResponseInterface
     */
    public function chart(): ResponseInterface
    {
        return $this->success('success',[
            'x' => [
                "07-24",
                "07-23",
                "07-22",
                "07-21",
                "07-20",
                "07-19",
                "07-18",
                "07-17",
                "07-16",
                "07-15",
                "07-14",
                "07-13",
                "07-12",
                "07-11",
                "07-10",
                "07-09",
                "07-08",
                "07-07",
                "07-06",
                "07-05",
                "07-04",
                "07-03",
                "07-02",
                "07-01",
                "06-30",
                "06-29",
                "06-28",
                "06-27",
                "06-26",
                "06-25",
            ],
            'y' => [
                51, 0, 1, 1, 0, 2, 1, 0, 0, 0, 2, 0, 1, 0, 5, 1, 0, 0, 0, 1, 0, 0, 4, 6, 0,
                0, 0, 0, 0, 0,
            ],
        ]);
    }

    /**
     * 商品统计
     * @return ResponseInterface
     */
    public function goods(): ResponseInterface
    {
        return $this->success('success',[
            "goods" => [
                [
                    "label" => "审核中",
                    "value "=> 1
                ],
                [
                    "label" => "销售中",
                    "value "=> 28
                ],
                [
                    "label" => "已下架",
                    "value "=> 23
                ],
                [
                    "label" => "库存预警",
                    "value "=> 1
                ],
            ],
            'order' => [
                [
                    "label" => "待付款",
                    "value "=> 171
                ],
                [
                    "label" => "待发货",
                    "value "=> 2
                ],
                [
                    "label" => "已发货",
                    "value "=> 113
                ],
                [
                    "label" => "退款中",
                    "value "=> 22
                ],
            ],
        ]);
    }
}