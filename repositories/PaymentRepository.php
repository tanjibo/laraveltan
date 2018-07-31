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


use App\Foundation\Lib\Payment\ExperiencePayment;
use App\Models\ExperienceBooking;


class PaymentRepository
{


    public function experienceUnifiedOrder( ExperienceBooking $order )
    {
        //类型,判断普通，山云荟，星月阁

        $val = [
            'body'   => '了如三舍安吉体验中心',
            'fee'    => $order->real_price,
            'number' => $this->orderNumber($order),
            'notify' => '/api/mini/callback/' . $order->id,
            'openid' => auth()->user()->mini_open_id,
        ];

        return ExperiencePayment::unifiedorder($val);

    }

    private function orderNumber( $order )
    {

        $prefix = strtolower(substr(strrchr(get_class($order), '\\'), 1));
        $prefix = ($prefix == 'epxeriencebooking' ? 'EN' : 'TR');
        switch ( app()->environment() ) {
            case 'test':
                return $prefix . '_test_' . str_pad($order->id, 12, '0', STR_PAD_LEFT);

            case 'local':
                return $prefix . '_local_' . str_pad($order->id, 12, '0', STR_PAD_LEFT);

            default:
                return $prefix . '_pro_' . str_pad($order->id, 12, '0', STR_PAD_LEFT);
        }

    }


    /**
     * @return array|bool
     * 安吉支付回调
     */
    public function experienceNotify()
    {
        return ExperiencePayment::notify();
    }

}