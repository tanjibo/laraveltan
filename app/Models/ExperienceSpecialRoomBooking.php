<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 18 Sep 2017 15:10:45 +0800.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class ExperienceSpecialRoomBooking
 * 
 * @property int $id
 * @property int $user_id
 * @property int $room_id
 * @property \Carbon\Carbon $date
 * @property string $time_quantum
 * @property string $start_time
 * @property string $end_time
 * @property string $customer
 * @property int $gender
 * @property string $mobile
 * @property int $peoples
 * @property int $fee
 * @property int $discount
 * @property string $dot
 * @property int $real_fee
 * @property int $pay_mode
 * @property string $tips
 * @property string $requirements
 * @property string $remark
 * @property int $status
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * @property int $balance
 * @property int $type
 * @property string $partner
 *
 * @package App\Models
 */
class ExperienceSpecialRoomBooking extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;
	use ExperienceRoomBookingTrait;
	protected $table = 'experience_special_room_booking';


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

	protected $casts = [
		'user_id' => 'int',
		'room_id' => 'int',
		'gender' => 'int',
		'peoples' => 'int',
		'fee' => 'int',
		'discount' => 'int',
		'real_fee' => 'int',
		'pay_mode' => 'int',
		'status' => 'int',
		'balance' => 'int',
		'type' => 'int'
	];

	protected $dates = [
		'date'
	];

	protected $fillable = [
		'user_id',
		'room_id',
		'date',
		'time_quantum',
		'start_time',
		'end_time',
		'customer',
		'gender',
		'mobile',
		'peoples',
		'fee',
		'discount',
		'dot',
		'real_fee',
		'pay_mode',
		'tips',
		'requirements',
		'remark',
		'status',
		'balance',
		'type',
		'partner'
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
     * 计算价格 dot 18,19
     */
    public static function countPrice( $data )
    {
        $timeDot = explode(',', $data);

        if (!$timeDot) return 0;

        $price=0;
        foreach ( $timeDot as $v ) {
            $price+= $v<static::PRICE_LIMIT_TIME_POINT? static::PRICE_TIME_BEFORE:static::PRICE_TIME_AFTER;
        }
        return $price;
    }

    /**
     * @param array $data
     * @return $this|bool|\Illuminate\Database\Eloquent\Model
     *  数据处理放在观察者 ExperienceRoomBookingShanObserver 中
     */
    public function store(array $data){
         if($model=static::query()->create($data)) return $model;
         return false;
    }
}
