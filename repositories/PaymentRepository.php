<?php
/**
 * |--------------------------------------------------------------------------
 * |
 * |--------------------------------------------------------------------------
 * Created by PhpStorm.
 * User: weaving
 * Date: 16/10/2017
 * Time: 11:55 AM
 */

namespace Repositories;


use App\Foundation\Lib\Payment;
use App\Models\ExperienceBooking;
use Illuminate\Support\Facades\App;


class PaymentRepository
{


    public function unifiedorder( ExperienceBooking $order )
    {
        //类型,判断普通，山云荟，星月阁
        extract(
            [
                'body'   => '了如三舍安吉体验中心',
                'fee'    => $order->real_price,
                'number' => (App::environment()=='local'||App::environment()=='test')?'E' . str_pad($order->id, 12, '0', STR_PAD_LEFT):'EP' . str_pad($order->id, 12, '0', STR_PAD_LEFT),
                'notify' => '/api/mini/callback/' . $order->id,
            ]
        );

        return Payment::unifiedorder($number, $fee, $body, $notify);

    }

    /**
     * @return array|bool
     * 微信支付回调
     */
    public function notify()
    {
        $xml = file_get_contents("php://input");

        $data = Payment::xml2array($xml);
        $sign = $data[ 'sign' ];
        unset($data[ 'sign' ]);

        if ($sign == Payment::createSign($data)) {
            $returnXml = Payment::array2xml([ 'return_code' => 'SUCCESS' ]);
            if ($data[ "return_code" ] == "FAIL") {
                $data[ 'message' ] = '通信出错';
            }
            else if ($data[ "result_code" ] == "FAIL") {
                $data[ 'message' ] = '业务出错';
            }
            else {
                $data[ 'message' ] = '支付成功';
            }
        }
        else {
            $returnXml = Payment::array2xml([ 'return_code' => 'FAIL', 'return_msg' => '签名失败' ]);
            $data      = false;
        }
// 返回结果给微信
        echo $returnXml;
        return $data;
    }
}