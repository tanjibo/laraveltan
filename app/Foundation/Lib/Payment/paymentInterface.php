<?php
/**
 * |--------------------------------------------------------------------------
 * |
 * |--------------------------------------------------------------------------
 * Created by PhpStorm.
 * User: weaving
 * Date: 31/7/2018
 * Time: 3:32 PM
 */

namespace App\Foundation\Lib\Payment;


interface paymentInterface
{
    public static function payConfig();

    public static function unifiedorder( array $arr );

    public static function refund( $number, $fee );

    public static function notify();



}