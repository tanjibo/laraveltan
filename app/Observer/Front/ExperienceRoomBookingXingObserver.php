<?php
/**
 * |--------------------------------------------------------------------------
 * |
 * |--------------------------------------------------------------------------
 * Created by PhpStorm.
 * User: weaving
 * Date: 11/10/2017
 * Time: 7:04 PM
 */

namespace App\Observer\Front;


use App\Models\ExperienceSpecialRoomBookingXinyuege;
use App\Models\User;

class ExperienceRoomBookingXingObserver
{
    public function creating( ExperienceSpecialRoomBookingXinyuege $booking )
    {
        //计算多少钱
        $booking->real_price = $total = $booking->price = 1000;

        if ($booking->pay_mode == $booking::PAY_MODE_BALANCE) {
            $account = User::accountInfo($booking->user_id);
            if ($account[ 'balance' ] < $total) {
                $booking->balance    = $account[ 'balance' ];
                $booking->real_price = $total - $account[ 'balance' ];
            }
            else {
                $booking->balance    = $total;
                $booking->real_price = 0;
            }
            // 扣除余额
            $user          = User::select('id', 'balance')->find($booking->user_id);
            $user->balance -= $booking->balance;
            $user->save();
        }
        else {
            $booking->real_price = $total;
        }

        $booking->status = $booking::STATUS_UNPAID;



    }


    public function updating( ExperienceSpecialRoomBookingXinyuege $booking )
    {
        switch ( $this->request->status ) {
            // 已支付
            case $booking::STATUS_PAID:
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

    /**
     * @param $booking
     * 已支付
     */
    private static function statusToPaid( $booking )
    {
        //  账户记录
        if ($booking->balance > 0) {
            // 扣除余额
            $user          = User::query()->select('id', 'balance')->find($booking->user_id);
            $user->balance -= $booking->balance;
            $user->save();

            AccountRecord::query()->create(
                [
                    'user_id' => $booking->user_id,
                    'amount'  => -$booking->balance,
                    'type'    => AccountRecord::TYPE_BALANCE,
                ]
            )
            ;
        }
        if ($booking->real_price > 0) {
            AccountRecord::query()->create(
                [
                    'user_id' => $booking->user_id,
                    'amount'  => -$booking->real_price,
                    'type'    => AccountRecord::TYPE_CASH,
                ]
            )
            ;
        }
    }

    /**
     * @param $booking
     * 已完成
     */
    private static function statusToComplete( $booking )
    {
        // 增加用户积分
        $credit               = User::consume2credit($booking->real_price);
        $user                 = User::query()->select('id', 'total_credit', 'surplus_credit')->find($booking->user_id);
        $user->total_credit   += $credit;
        $user->surplus_credit += $credit;
        $user->save();

        // 积分记录
        CreditLog::query()->create(
            [
                'user_id'    => $user->id,
                'credit'     => $credit,
                'event'      => '预订安吉体验中心获得积分',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        )
        ;
    }

    /**
     * @param $booking
     * 取消订单
     */
    public static function statusToCancel( $booking )
    {
        // 账户记录
        if ($booking->balance > 0) {
            // 返还余额
            $user          = User::query()->select('id', 'balance')->find($booking->user_id);
            $user->balance += $booking->balance;
            $user->save();

            AccountRecord::query()->create(
                [
                    'user_id' => $booking->user_id,
                    'amount'  => $booking->balance,
                    'type'    => AccountRecord::TYPE_BALANCE,
                ]
            )
            ;
        }
        if ($booking->real_price > 0) {
            AccountRecord::query()->create(
                [
                    'user_id' => $booking->user_id,
                    'amount'  => $booking->real_price,
                    'type'    => AccountRecord::TYPE_CASH,
                ]
            )
            ;
        }
    }


    /**
     * @param ExperienceBooking $booking
     * 更新完成状态后发送短信,微信通知
     */
    public function updated( ExperienceBooking $booking )
    {
        event(new SendNotificationEvent($booking));
    }
}