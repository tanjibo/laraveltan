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
	protected $table = 'experience_special_room_booking';

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
}
