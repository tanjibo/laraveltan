<?php

namespace App\Foundation\Lib\Payment;

/**
 * |--------------------------------------------------------------------------
 * |
 * |--------------------------------------------------------------------------
 * Created by PhpStorm.
 * User: weaving
 * Date: 31/7/2018
 * Time: 2:03 PM
 */


class ExperiencePayment extends Payment implements paymentInterface
{

    static function payConfig()
    {
        return [
            'appid'         => config('wechat.mini_program.experience.app_id'),
            'mch_id'        => config('pay.experience.mch_id'),
            'pay_key'       => config('pay.experience.pay_key'),
            'ssl_cert_path' => storage_path() . '/wechatKey/apiclient_cert.pem',
            'ssl_key_path'  => storage_path() . '/wechatKey/apiclient_key.pem',
        ];
    }

    static public function unifiedorder( array $arr )
    {
        $config = static::payConfig();
        $arr    = array_merge($config, $arr);
        return parent::unifiedorder($arr);

    }

    static public function refund( $number, $fee )
    {
        $config             = static::payConfig();
        $config[ 'number' ] = $number;
        $config[ 'fee' ]    = $fee;
        return parent::orderRefund($config);
    }

    static public function notify()
    {
        return static::wechatNotify(config('pay.experience.pay_key'));
    }

}