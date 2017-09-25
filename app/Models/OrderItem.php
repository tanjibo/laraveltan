<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 18 Sep 2017 15:10:45 +0800.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class OrderItem
 * 
 * @property int $order_id
 * @property int $item_id
 * @property int $type
 * @property int $quantity
 * 
 * @property \App\Models\Order $order
 *
 * @package App\Models
 */
class OrderItem extends Eloquent
{
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'order_id' => 'int',
		'item_id' => 'int',
		'type' => 'int',
		'quantity' => 'int'
	];

	protected $fillable = [
		'order_id',
		'item_id',
		'type',
		'quantity'
	];

	public function order()
	{
		return $this->belongsTo(\App\Models\Order::class);
	}
}
