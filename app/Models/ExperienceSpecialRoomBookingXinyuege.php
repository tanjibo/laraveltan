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
	protected $table = 'experience_special_room_booking_xinyuege';

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
}
