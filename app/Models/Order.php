<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 18 Sep 2017 15:10:45 +0800.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Order
 * 
 * @property int $id
 * @property int $user_id
 * @property int $province_id
 * @property int $city_id
 * @property string $number
 * @property int $order_fee
 * @property int $postage
 * @property int $balance
 * @property int $discount
 * @property int $real_fee
 * @property string $consignee
 * @property string $mobile
 * @property string $address
 * @property int $pay_mode
 * @property int $device
 * @property string $tips
 * @property string $remark
 * @property int $type
 * @property int $status
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * 
 * @property \App\Models\City $city
 * @property \Illuminate\Database\Eloquent\Collection $invoices
 * @property \App\Models\OrderExpress $order_express
 * @property \App\Models\OrderItem $order_item
 * @property \App\Models\OrderLog $order_log
 * @property \App\Models\OrderRefund $order_refund
 *
 * @package App\Models
 */
class Order extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;
	protected $table = 'order';

	protected $casts = [
		'user_id' => 'int',
		'province_id' => 'int',
		'city_id' => 'int',
		'order_fee' => 'int',
		'postage' => 'int',
		'balance' => 'int',
		'discount' => 'int',
		'real_fee' => 'int',
		'pay_mode' => 'int',
		'device' => 'int',
		'type' => 'int',
		'status' => 'int'
	];

	protected $fillable = [
		'user_id',
		'province_id',
		'city_id',
		'number',
		'order_fee',
		'postage',
		'balance',
		'discount',
		'real_fee',
		'consignee',
		'mobile',
		'address',
		'pay_mode',
		'device',
		'tips',
		'remark',
		'type',
		'status'
	];

	public function city()
	{
		return $this->belongsTo(\App\Models\City::class);
	}

	public function invoices()
	{
		return $this->hasMany(\App\Models\Invoice::class);
	}

	public function order_express()
	{
		return $this->hasOne(\App\Models\OrderExpress::class);
	}

	public function order_item()
	{
		return $this->hasOne(\App\Models\OrderItem::class);
	}

	public function order_log()
	{
		return $this->hasOne(\App\Models\OrderLog::class);
	}

	public function order_refund()
	{
		return $this->hasOne(\App\Models\OrderRefund::class);
	}
}
