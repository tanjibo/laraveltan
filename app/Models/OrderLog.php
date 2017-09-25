<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 18 Sep 2017 15:10:45 +0800.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class OrderLog
 * 
 * @property int $order_id
 * @property string $event
 * @property string $handler
 * @property \Carbon\Carbon $created_at
 * 
 * @property \App\Models\Order $order
 *
 * @package App\Models
 */
class OrderLog extends Eloquent
{
	protected $table = 'order_log';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'order_id' => 'int'
	];

	protected $fillable = [
		'order_id',
		'event',
		'handler'
	];

	public function order()
	{
		return $this->belongsTo(\App\Models\Order::class);
	}
}
