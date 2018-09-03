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

    }

    public static function notify(){
        return static::wechatNotify(config('pay.tearoom.pay_key'));
    }


}