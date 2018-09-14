<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 18 Sep 2017 15:10:45 +0800.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * Class TearoomBooking
 *
 * @property int $id
 * @property int $user_id
 * @property int $tearoom_id
 * @property \Carbon\Carbon $date
 * @property string $time
 * @property int $start_point
 * @property int $end_point
 * @property string $customer
 * @property int $gender
 * @property string $mobile
 * @property int $peoples
 * @property int $fee
 * @property int $discount
 * @property int $real_fee
 * @property int $pay_mode
 * @property string $tips
 * @property string $remark
 * @property int $status
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 *
 * @property \App\Models\Tearoom $tearoom
 * @property \App\Models\User $user
 *
 * @package App\Models
 */
class TearoomBooking extends Eloquent
{

    use \Illuminate\Database\Eloquent\SoftDeletes;
    protected $table = 'tearoom_booking';
    use ModelTrait;

    /**
     * 订单状态
     */

    const STATUS_UNPAID   = 0;     // 待支付
    const STATUS_PAID     = 1;     // 已支付
    const STATUS_COMPLETE = 10;  // 已完成
    const STATUS_CANCEL   = -10; // 已取消
    const STATUS_DEL   = -100;   // 已删除
    const STATUS_ALL  = 100; // 已取消


    /**
     * 支付方式
     */
    const PAY_MODE_OFFLINE = 0;    // 线下支付
    const PAY_MODE_WECHAT  = 1;    // 微信支付
    const PAY_MODE_BALANCE = 10;   // 余额支付

    const STATUS_NO_REFUND = 0;    // 不用退款
    const STATUS_UNREFUND  = 1;    // 未退款
    const STATUS_REFUNDED =2 ;   // 已退款


    protected $casts
        = [
            'user_id'     => 'int',
            'tearoom_id'  => 'int',
            'start_point' => 'int',
            'end_point'   => 'int',
            'gender'      => 'int',
            'peoples'     => 'int',
            'fee'         => 'int',
            'discount'    => 'int',
            'real_fee'    => 'int',
            'pay_mode'    => 'int',
            'status'      => 'int',
        ];

    protected $dates
        = [
            'date',
        ];

    protected $fillable
        = [
            'user_id',
            'tearoom_id',
            'date',
            'time',
            'start_point',
            'end_point',
            'customer',
            'gender',
            'mobile',
            'peoples',
            'fee',
            'discount',
            'real_fee',
            'pay_mode',
            'tips',
            'remark',
            'status',
            'is_prepay',
            'is_refund',
            'is_discount',
            'balance'
        ];

    public function setDateAttribute( $val )
    {
        $this->attributes[ 'date' ] = date('Y-m-d', strtotime($val));;
    }

    public function getDateAttribute( $val )
    {
        return date('Y-m-d', strtotime($val));
    }


    static function statusToText( $status )
    {
        switch ( $status ) {
            case static::STATUS_UNPAID:
                return "待支付";
            case static::STATUS_PAID:
                return "已支付";
            case static::STATUS_COMPLETE:
                return "已完成";
            case static::STATUS_CANCEL :
                return "已取消";
        }
    }

    public function tearoom()
    {
        return $this->belongsTo(\App\Models\Tearoom::class);
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }


    public function tearoom_booking_requirements()
    {
        return $this->hasMany(TearoomBookingRequirement::class, 'booking_id');
    }


    public static function changeStatus( TearoomBooking $booking )
    {
        if (!$booking)
            return false;

        //删除订单
//        if (request()->status == static::STATUS_CANCEL) {
//            $booking->delete();
//            return true;
//        }

        if ($booking->status == request()->status) {
            return true;
        }


        $booking->status = request()->status;
        return $booking->save();
    }


}
