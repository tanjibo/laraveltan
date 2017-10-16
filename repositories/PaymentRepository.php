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
use Illuminate\Http\Request;


class PaymentRepository
{


    public  function unifiedorder(ExperienceBooking $order, Request $request){
        //类型,判断普通，山云荟，星月阁
        $type=$request->type?:1;

        extract([
                    'body'    => '了如三舍安吉体验店',
                    'fee'     => $order->real_price,
                    'number'  => 'E' . str_pad($order->id, 12, '0', STR_PAD_LEFT),
                    'notify'  => '/pay/notify/type/experience/id/' . $order->id,
                ]);

        Payment::unifiedorder($number,$fee,$body,$notify);


    }
}