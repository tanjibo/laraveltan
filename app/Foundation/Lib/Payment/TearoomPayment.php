<?php
/**
 * |--------------------------------------------------------------------------
 * |
 * |--------------------------------------------------------------------------
 * Created by PhpStorm.
 * User: weaving
 * Date: 7/8/2018
 * Time: 4:11 PM
 */

namespace App\Foundation\Lib\Payment;


use Carbon\Carbon;

class TearoomPayment extends Payment implements paymentInterface
{
    public static function payConfig(){
        return [
            'appid'         => config('wechat.mini_program.tearoom.app_id'),
            'mch_id'        => config('pay.tearoom.mch_id'),
            'pay_key'       => config('pay.tearoom.pay_key'),
            'ssl_cert_path' => storage_path() . '/wechatKey/apiclient_cert.pem',
            'ssl_key_path'  => storage_path() . '/wechatKey/apiclient_key.pem',
        ];
    }

    public static function unifiedorder( array $arr ){
        $config = static::payConfig();
        $arr    = array_merge($config, $arr);
        return parent::unifiedorder($arr);
    }

    public static function refund( $number, $fee ){
        $config             = static::payConfig();
        $config[ 'number' ] = $number;
        $config[ 'fee' ]    = $fee > 100 ? $fee : 0.1;
        return parent::orderRefund($config);
    }

    public static function notify(){
        return static::wechatNotify(config('pay.tearoom.pay_key'));
    }

    /**
     * @param $date
     * 退款规则
     */
    static public function refundFeeRegular( $date )
    {
        $date = new Carbon($date);
        //大于五天
        if (Carbon::now()->addDay(5)->lt($date)) {
            return 1;
        } //小于五天大于三天
        elseif (Carbon::now()->addDay(3)->lt($date)) {
            return 0.5;
        }
        elseif (Carbon::now()->addDay(1)->lt($date)) {
            return 0.2;
        }
        else {
            return 0;
        }

    }

}