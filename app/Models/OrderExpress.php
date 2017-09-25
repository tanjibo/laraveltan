<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 18 Sep 2017 15:10:45 +0800.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class OrderExpress
 * 
 * @property int $order_id
 * @property string $number
 * @property int $type
 * @property string $remark
 * @property \Carbon\Carbon $created_at
 * 
 * @property \App\Models\Order $order
 *
 * @package App\Models
 */
class OrderExpress extends Eloquent
{
	protected $table = 'order_express';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'order_id' => 'int',
		'type' => 'int'
	];

	protected $fillable = [
		'order_id',
		'number',
		'type',
		'remark'
	];

	public function order()
	{
		return $this->belongsTo(\App\Models\Order::class);
	}
}
