<?php
/**
 * |--------------------------------------------------------------------------
 * |
 * |--------------------------------------------------------------------------
 * Created by PhpStorm.
 * User: weaving
 * Date: 29/9/2017
 * Time: 5:16 PM
 */

namespace App\Observer\Experience;


use App\Events\RefundFailNotificationEvent;
use App\Events\SendNotificationEvent;
use App\Foundation\Lib\Payment;
use App\Jobs\SendBookingEmail;
use App\Jobs\SendRefundFailEmail;
use App\Models\AccountRecord;
use App\Models\CreditLog;
use App\Models\ExperienceBooking;

use App\Models\ExperienceRefund;
use App\Models\Partners;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class BookingApiObserver
{
    public $request;

    public function __construct( Request $request )
    {
        $this->request = $request;
    }

    public function creating( ExperienceBooking $booking )
    {

        $booking->user_id = Auth::id() ?: 1;
        //计算价格
        if (App::environment() == 'test' || App::environment() == 'develop') {
            $booking->price = $total = 0.1;
        }
        else {
            $total = $booking::calculateFee($booking->checkin, $booking->checkout, $this->request->rooms, $this->request->isPrepay);

            $booking->price = $booking::calculateFee($booking->checkin, $booking->checkout, $this->request->rooms, false);
        }

        //如果是余额支付
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
        }
        else {
            $booking->real_price = $total;
        }

        $booking->checkin   = date('Y-m-d', strtotime($this->request->checkin));
        $booking->checkout  = date('Y-m-d', strtotime($this->request->checkout));
        $booking->status    = $booking::STATUS_UNPAID;
        $booking->is_prepay = $this->request->isPrepay;
        $booking->source=static::isComingPartner($this->request->partnerToken);

    }

    /**
     * @param $token
     * @return string
     * 判断是否第三方合作来源
     */
    public static function isComingPartner( $token )
    {
        if ($token) {
            $name = Partners::query()->token($token)->value('name');
            return $name ?: "mini";
        }
        return 'mini';
    }


    public function updating( ExperienceBooking $booking )
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
                static::statusToCancel($booking, $this->request);
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
    public static function statusToCancel( $booking, $request )
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

        //退款------------  暂时不用自动退款，改成人工手退
        if (isset($request->status) && $booking->status == ExperienceBooking::STATUS_CANCEL) {


            $result = App::environment() == 'local' ? [ 'result_code' => '' ] : Payment::refund('E' . str_pad($booking->id, 12, '0', STR_PAD_LEFT), $booking->real_price);

            if ($result[ 'result_code' ] == 'SUCCESS') {
                //更改订单状态为已退款
                $booking->is_refund=ExperienceBooking::STATUS_REFUNDED;
                $booking->status=ExperienceBooking::STATUS_CANCEL;
                ExperienceRefund::query()->create($result);
            }
            else {
                //发送邮件通知 https://d.laravel-china.org/docs/5.5/notifications#introduction

                $booking->is_refund=ExperienceBooking::STATUS_UNREFUND;
                event(new RefundFailNotificationEvent($booking));
                //队列发送--------------有点问题-------------放弃了
                //SendRefundFailEmail::dispatch($booking);
            }

        }

    }


    /**
     * @param ExperienceBooking $booking
     * 更新完成状态后发送短信,微信通知
     */
    public function updated( ExperienceBooking $booking )
    {

        event(new SendNotificationEvent($booking));
        // SendBookingEmail::dispatch($booking);
    }
}