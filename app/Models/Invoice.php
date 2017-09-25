<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 18 Sep 2017 15:10:45 +0800.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Invoice
 * 
 * @property int $id
 * @property int $order_id
 * @property int $type
 * @property string $title
 * @property int $case
 * 
 * @property \App\Models\Order $order
 *
 * @package App\Models
 */
class Invoice extends Eloquent
{
	protected $table = 'invoice';
	public $timestamps = false;

	protected $casts = [
		'order_id' => 'int',
		'type' => 'int',
		'case' => 'int'
	];

	protected $fillable = [
		'order_id',
		'type',
		'title',
		'case'
	];

	public function order()
	{
		return $this->belongsTo(\App\Models\Order::class);
	}
}
