<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 18 Sep 2017 15:10:45 +0800.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class ExperienceSpecialRoomBookingXinyuege
 * 
 * @property int $id
 * @property int $user_id
 * @property \Carbon\Carbon $date
 * @property int $price
 * @property int $balance
 * @property int $real_price
 * @property int $pay_mode
 * @property string $customer
 * @property int $gender
 * @property string $mobile
 * @property int $people
 * @property string $requirements
 * @property string $remark
 * @property int $status
 * @property \Carbon\Carbon $created_at
 * @property int $room_id
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * @property int $type
 * 
 * @property \App\Models\User $user
 *
 * @package App\Models
 */
class ExperienceSpecialRoomBookingXinyuege extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;
	use  ExperienceRoomBookingTrait;
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

	protected $casts = [
		'user_id' => 'int',
		'price' => 'int',
		'balance' => 'int',
		'real_price' => 'int',
		'pay_mode' => 'int',
		'gender' => 'int',
		'people' => 'int',
		'status' => 'int',
		'room_id' => 'int',
		'type' => 'int'
	];

	protected $dates = [
		'date'
	];

	protected $fillable = [
		'user_id',
		'date',
		'price',
		'balance',
		'real_price',
		'pay_mode',
		'customer',
		'gender',
		'mobile',
		'people',
		'requirements',
		'remark',
		'status',
		'room_id',
		'type'
	];

	public function user()
	{
		return $this->belongsTo(\App\Models\User::class);
	}

    /**
     * @param $value 格式化时间
     */
    public function setDateAttribute( $value )
    {
        $this->attributes[ 'date' ] = date('Y-m-d', strtotime($value));
    }

    /**
     * Set attribute with price
     *
     * @param  string $value
     * @return void
     */
    public function setPriceAttribute( $value )
    {
        $this->attributes[ 'price' ] = intval($value * 100);
    }

    /**
     * Get attribute with price
     *
     * @param  string $value
     * @return void
     */
    public function getPriceAttribute( $value )
    {
        return doubleval($value / 100);
    }

    /**
     * Set attribute with real_price
     *
     * @param  string $value
     * @return void
     */
    public function setRealPriceAttribute( $value )
    {
        $this->attributes[ 'real_price' ] = intval($value * 100);
    }

    /**
     * Get attribute with real_price
     *
     * @param  string $value
     * @return void
     */
    public function getRealPriceAttribute( $value )
    {
        return doubleval($value / 100);
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
}
