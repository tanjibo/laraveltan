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
use App\Foundation\Lib\Payment\TearoomPayment;
use App\Models\ExperienceBooking;
use App\Models\PaymentLog;
use App\Models\TearoomBooking;


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


    public function tearoomUnifiedOrder( TearoomBooking $order )
    {
        //类型,判断普通，山云荟，星月阁

        $val = [
            'body'   => '了如三舍安定门茶空间',
            'fee'    => $order->real_fee,
            'number' => $this->orderNumber($order),
            'notify' => '/api/mini/callback/' . $order->id,
            'openid' => auth()->user()->tearoom_open_id,
        ];

        return TearoomPayment::unifiedorder($val);
    }

    private function orderNumber( $order )
    {

        $prefix = strtolower(substr(strrchr(get_class($order), '\\'), 1));
        $prefix = ($prefix == 'tearoombooking' ? 'TR' : 'EN');
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
     * @param ExperienceBooking $order
     * @return array|bool
     * 退款
     */
    public function experienceRefund( ExperienceBooking $order )
    {
//        return ExperiencePayment::refund($this->orderNumber($order), $order->real_price);
        if (!$percent = ExperiencePayment::refundFeeRegular($order->checkin)) {
            return [ 'result_code' => 'SUCCESS' ];
        }
        return ExperiencePayment::refund($this->orderNumber($order), number_format($order->real_price * $percent, 2));
    }

    /**
     * @param ExperienceBooking $order
     * @return array|bool
     * 退款
     */
    public function tearoomRefund( TearoomBooking $order )
    {
        if (!$percent = TearoomPayment::refundFeeRegular($order->date)) {
            return [ 'result_code' => 'SUCCESS' ];
        }
        return TearoomPayment::refund($this->orderNumber($order), number_format($order->real_price*$percent, 2));
    }

    /**
     * @return array|bool
     * 安吉支付回调
     */
    public function experienceNotify()
    {
        return ExperiencePayment::notify();
    }

    /**
     * @return array|bool
     * 安吉支付回调
     */
    public function tearoomNotify()
    {
        return TearoomPayment::notify();
    }


    public function tearoomCallBack( $callback, $booking_id )
    {
        if (!$booking = \App\Models\Api\TearoomBooking::query()->find($booking_id))
            return false;
        \App\Models\Api\TearoomBooking::changeOrderStatusToApi($booking, TearoomBooking::STATUS_PAID);
        $this->paymentLog($callback, 4);

    }


    public function experienceCallBack( $callback, $booking_id )
    {
        ExperienceBooking::changeBookingOrder($booking_id, ExperienceBooking::STATUS_PAID);

        $this->paymentLog($callback, 3);
    }



    private function paymentLog( array $callback, $type )
    {
        extract($callback);
        if (!PaymentLog::query()->where('order_number', $out_trade_no)->count()) {
            PaymentLog::query()->create(
                [
                    'order_number' => $out_trade_no,
                    'trade_number' => $transaction_id,
                    'fee'          => $total_fee / 100,
                    'type'         => $type, //小程序
                    'created_at'   => date('Y-m-d H:i:s'),
                ]
            )
            ;

        }
    }

}