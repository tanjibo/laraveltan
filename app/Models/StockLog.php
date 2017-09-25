<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 18 Sep 2017 15:10:45 +0800.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class StockLog
 * 
 * @property int $stock_id
 * @property string $event
 * @property int $quantity
 * @property string $handler
 * @property \Carbon\Carbon $created_at
 * 
 * @property \App\Models\Stock $stock
 *
 * @package App\Models
 */
class StockLog extends Eloquent
{
	protected $table = 'stock_log';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'stock_id' => 'int',
		'quantity' => 'int'
	];

	protected $fillable = [
		'stock_id',
		'event',
		'quantity',
		'handler'
	];

	public function stock()
	{
		return $this->belongsTo(\App\Models\Stock::class);
	}
}
