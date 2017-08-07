<?php

namespace App\Api;

use Illuminate\Database\Eloquent\Model;

class ExperienceSpecialBookingXin extends Model
{

    protected $table = 'experience_special_room_booking_xinyuege';


    const STATUS_UNPAID   = 0;    // 待支付
    const STATUS_PAID     = 1;    // 已支付
    const STATUS_CHECKIN  = 2;    // 已入住
    const STATUS_COMPLETE = 10;   // 已完成
    const STATUS_CANCEL   = -10;  // 已取消

    /**
     * 支付方式
     */
    const PAY_MODE_OFFLINE = 0;    // 线下支付
    const PAY_MODE_WECHAT  = 1;    // 微信支付
    const PAY_MODE_BALANCE = 10;   // 余额支付


    /**
     * 性别
     */
    const GENDER_UNKNOW = 0;    // 未知
    const GENDER_MALE   = 1;    // 男
    const GENDER_FEMALE = 2;    // 女

    protected $fillable
        = [
            'user_id',
            'room_id',
            'date',
            'customer',
            'gender',
            'mobile',
            'people',
            'requirements',
            'price',
            'discount',
            'real_price',
            'pay_mode',
            'remark',
            'status',
            'balance',

        ];



    /**
     * @param $id
     * @param $status
     * @return bool  供修改订单使用
     */
    public static function changeStatus( $id, $status )
    {
        if (!$booking = self::find($id)) {
            return false;
        }

        if ($booking->status == $status) {
            return true;
        }

        switch ( $status ) {


            // 取消预约
            case self::STATUS_CANCEL:


                if ($booking->balance > 0) {
                    // 返还余额
                    $user          = User::select('id', 'balance')->find($booking->user_id);
                    $user->balance += $booking->balance;
                    $user->save();

                }
                break;
        }

        $booking->status = $status;
        return $booking->save();
    }
}
