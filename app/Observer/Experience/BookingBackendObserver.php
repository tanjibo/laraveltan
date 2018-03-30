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

use App\Events\SendNotificationEvent;
use App\Models\AccountRecord;
use App\Models\CreditLog;
use App\Models\ExperienceBooking;

use App\Models\ExperienceRefund;
use App\Models\ExperienceRoom;
use App\Models\ExperienceSpecialPrice;
use App\Models\User;
use App\Repositories\ExperienceBookingRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class BookingBackendObserver
{
    public $request;

    public function __construct( Request $request )
    {
        $this->request = $request;
    }

    public function creating( ExperienceBooking $booking )
    {
        $booking->user_id = Auth::id();
        $booking->status  = $booking::STATUS_UNPAID;
        $booking->real_price = $booking->price = $total = static::calculateFee($booking->checkin, $booking->checkout, $this->request->rooms);

        //如果是余额支付
        $booking->checkin  = date('Y-m-d', strtotime($this->request->checkin));
        $booking->checkout = date('Y-m-d', strtotime($this->request->checkout));
        $booking->pay_mode = $booking::PAY_MODE_OFFLINE;

    }

    public function created(ExperienceBooking $booking){
        event(new SendNotificationEvent($booking));
    }

    public function saving( ExperienceBooking $booking )
    {
        if(!$this->request->status){
            $booking->real_price = $booking->price = $total = static::calculateFee($booking->checkin, $booking->checkout, $this->request->rooms);
        }



    }

    public function updating( ExperienceBooking $booking )
    {

        switch ( $this->request->status?:"" ) {
            // 已支付
            case $booking::STATUS_PAID:

                $booking->real_price = $this->request->real_price;
                static::statusToPaid($booking);
                break;
                case $booking::STATUS_CHECKIN:

                $booking->real_price = $this->request->real_price;
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


    /**
     * @param $checkin
     * @param $checkout
     * @param array $room
     * @return int|mixed
     * 计算订单价格
     */
    private static function calculateFee( $checkin, $checkout, $room = [] )
    {
        $total = 0;
        $ds    = splitDate($checkin, $checkout);

        // 房间金额
        foreach ( $room as $v ) {
            $r = ExperienceRoom::query()->select('id', 'price', 'type')->find($v);

            foreach ( $ds as $date ) {
                // 节日价
                $special = ExperienceSpecialPrice::query()->where('experience_room_id', $r->id)->where('date', $date)->value('price');
                $total   += $special === null ? $r->price : $special;
            }
        }

        return $total;
    }
}