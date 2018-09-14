<?php
/**
 * |--------------------------------------------------------------------------
 * |
 * |--------------------------------------------------------------------------
 * Created by PhpStorm.
 * User: weaving
 * Date: 21/12/2017
 * Time: 6:13 PM
 */

namespace App\Observer\Tearoom;



use App\Jobs\SendTearoomBookingSm;
use App\Jobs\SendTearoomRefundFailEmail;
use App\Models\AccountRecord;
use App\Models\CreditLog;
use App\Models\Api\TearoomBooking;
use App\Models\TearoomPrice;
use App\Models\TearoomSchedule;
use App\Models\User;
use Repositories\PaymentRepository;
use Repositories\TearoomScheduleRepository;

class BookingApiObserver
{

    public function creating( TearoomBooking $booking )
    {


        $booking->user_id = auth()->id();

        $price = TearoomPrice::query()->find(request()->price_id);

        //算出截止时间点
        $booking->end_point = $price->durations > 0 ? request()->start_point + $price->durations : request()->start_point - $price->durations;

        //时间段
        $booking->time = TearoomSchedule::$timetable[ request()->start_point ] . ' - ' . TearoomSchedule::$timetable[ $booking->end_point ];
        //价格
        $booking->real_fee = $booking->fee = $price->fee;
        if(app()->environment(['local','test','develop'])){
            $booking->real_fee = $booking->fee = 0.1;
        }
        $booking->status   = TearoomBooking::STATUS_UNPAID;
        $booking->pay_mode = request()->pay_mode;

    }


    public function created( TearoomBooking $booking )
    {
        // 锁定时间
        app(TearoomScheduleRepository::class)->lockTime($booking->tearoom_id, $booking->date, $booking->start_point, $booking->end_point);
    }


    public function updating( TearoomBooking $booking )
    {

        switch ( request()->status ?: "" ) {
            // 已支付
            case $booking::STATUS_PAID:

                $booking->real_fee = request()->real_fee;
                static::statusToPaid($booking);
                break;
            // 已完成
            case $booking::STATUS_COMPLETE:
                static::statusToComplete($booking);
                break;
            // 已取消
            case $booking::STATUS_CANCEL:
                static::statusToCancel($booking);
                break;

        }
    }

    private function statusToPaid( TearoomBooking $booking )
    {
        // 余额支付
        if ($booking->pay_mode == $booking::PAY_MODE_BALANCE) {
            // 扣除余额
            $user          = User::query()->select('id', 'balance')->find($booking->user_id);
            $user->balance -= $booking->real_fee;
            $user->save();

            // 账户记录
            AccountRecord::query()->create(
                [
                    'user_id' => $booking->user_id,
                    'amount'  => -$booking->real_fee,
                    'type'    => AccountRecord::TYPE_BALANCE,
                ]
            )
            ;
        }

    }

    private static function statusToComplete( TearoomBooking $booking )
    {
        // 增加用户积分
        $credit               = User::consume2credit($booking->real_fee);
        $user                 = User::query()->select('id', 'total_credit', 'surplus_credit')->find($booking->user_id);
        $user->total_credit   += $credit;
        $user->surplus_credit += $credit;
        $user->save();

        // 积分记录
        CreditLog::query()->create(
            [
                'user_id'    => $user->id,
                'credit'     => $credit,
                'event'      => '茶舍消费获得积分',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        )
        ;

    }

    /**
     * @param $booking
     * 取消订单
     */
    public static function statusToCancel( TearoomBooking $booking )
    {
        // 解锁时间
        app(TearoomScheduleRepository::class)->unlockTime($booking->tearoom_id, $booking->date, $booking->start_point, $booking->end_point);

        // 余额支付
        if ( $booking->pay_mode == $booking::PAY_MODE_BALANCE) {
            // 返还余额
            $user          = User::query()->select('id', 'balance')->find($booking->user_id);
            $user->balance += $booking->real_fee;
            $user->save();

            // 账户记录
            AccountRecord::query()->create(
                [
                    'user_id' => $booking->user_id,
                    'amount'  => $booking->real_fee,
                    'type'    => AccountRecord::TYPE_BALANCE,
                ]
            )
            ;
        }
//
        //退款------------  暂时不用自动退款，改成人工手退
        if (isset(request()->status) && $booking->status == TearoomBooking::STATUS_CANCEL) {


            $result = app()->environment() == 'local' ? [ 'result_code' => '' ] :
                app(PaymentRepository::class)->tearoomRefund($booking);

            if ($result[ 'result_code' ] == 'SUCCESS') {
                //更改订单状态为已退款
                $booking->is_refund = TearoomBooking::STATUS_REFUNDED;
            }
            else {
                //发送邮件通知 https://d.laravel-china.org/docs/5.5/notifications#introduction

                $booking->is_refund = TearoomBooking::STATUS_UNREFUND;
             //   event(new RefundFailNotificationEvent($booking));
                //队列发送--------------有点问题-------------放弃了
                SendTearoomRefundFailEmail::dispatch($booking);
            }

        }


    }


    public function updated( TearoomBooking $booking )
    {
        //发送短信通知没有完成
        SendTearoomBookingSm::dispatch($booking);
       // event(new SendTearoomBackendNotificationEvent($booking));
    }

}