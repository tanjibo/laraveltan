<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 18 Sep 2017 15:10:45 +0800.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class OrderRefund
 * 
 * @property int $order_id
 * @property int $fee
 * @property int $created_at
 * 
 * @property \App\Models\Order $order
 *
 * @package App\Models
 */
class OrderRefund extends Eloquent
{
	protected $table = 'order_refund';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'order_id' => 'int',
		'fee' => 'int',
		'created_at' => 'int'
	];

	protected $fillable = [
		'order_id',
		'fee'
	];

	public function order()
	{
		return $this->belongsTo(\App\Models\Order::class);
	}
}
