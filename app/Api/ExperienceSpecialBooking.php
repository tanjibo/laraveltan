<?php

namespace App\Api;

use Illuminate\Database\Eloquent\Model;
use Repositories\ExperienceBookingSpecialRepository;

class ExperienceSpecialBooking extends Model
{
    use \Illuminate\Database\Eloquent\SoftDeletes;

    /**
     * 订单状态
     */

    const STATUS_UNPAID   = 0;     // 待支付
    const STATUS_PAID     = 1;     // 已支付
    const STATUS_COMPLETE = 10;  // 已完成
    const STATUS_CANCEL   = -10; // 已取消

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


    const PRICE_TIME_BEFORE = 2000;  //18点前的价格
    const PRICE_TIME_AFTER  = 2500;   //18 点后的价格

    const PRICE_LIMIT_TIME_POINT = '18'; //价格分界线的时间点

    protected $table = 'experience_special_room_booking';

    protected $fillable
        = [
            'user_id',
            'room_id',
            'date',
            'time',
            'start_time',
            'end_time',
            'customer',
            'gender',
            'mobile',
            'peoples',
            'fee',
            'discount',
            'real_fee',
            'pay_mode',
            'remark',
            'tips',
            'status',
            'dot',
            'time_quantum',
            'balance',
            'requirements'
        ];

    /**
     * Set attribute with fee
     *
     * @param  string $value
     * @return void
     */
    public function setFeeAttribute( $value )
    {
        $this->attributes[ 'fee' ] = intval($value * 100);
    }


    /**
     * @param $value 格式化时间
     */
    public function setDateAttribute( $value )
    {
        $this->attributes[ 'date' ] = date('Y-m-d', strtotime($value));
    }


    public function setBalanceAttribute($value)
    {
        $this->attributes['balance'] = intval($value * 100);
    }

    /**
     * Get attribute with balance
     *
     * @param  string  $value
     * @return void
     */
    public function getBalanceAttribute($value)
    {
        return doubleval($value / 100);
    }
    /**
     * Get attribute with fee
     *
     * @param  string $value
     * @return void
     */
    public function getFeeAttribute( $value )
    {
        return doubleval($value / 100);
    }

    /**
     * Set attribute with discount
     *
     * @param  string $value
     * @return void
     */
    public function setDiscountAttribute( $value )
    {
        $this->attributes[ 'discount' ] = intval($value * 100);
    }

    /**
     * Get attribute with discount
     *
     * @param  string $value
     * @return void
     */
    public function getDiscountAttribute( $value )
    {
        return doubleval($value / 100);
    }

    /**
     * Set attribute with real_fee
     *
     * @param  string $value
     * @return void
     */
    public function setRealFeeAttribute( $value )
    {
        $this->attributes[ 'real_fee' ] = intval($value * 100);
    }

    /**
     * Get attribute with fee
     *
     * @param  string $value
     * @return void
     */
    public function getRealFeeAttribute( $value )
    {
        return doubleval($value / 100);
    }

    /**
     * Relation to TearoomModel
     *
     * @return object
     */
    public function room()
    {
        return $this->belongsTo(ExperienceRoomModel::class, 'room_id','id')->select('id', 'name', 'type');
    }

    public function requirementsModel(){

        return $this->hasMany(ExperienceBookingRequirementsModel::class,'booking_id');
    }






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
                // 解锁时间
                ExperienceBookingSpecialRepository::unlockDot($booking);

                // 账户记录
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
