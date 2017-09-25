<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 18 Sep 2017 15:10:45 +0800.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class TransferStock
 * 
 * @property int $transfer_id
 * @property int $org_stock_id
 * @property int $new_stock_id
 * @property int $quantity
 * 
 * @property \App\Models\Stock $stock
 * @property \App\Models\Transfer $transfer
 *
 * @package App\Models
 */
class TransferStock extends Eloquent
{
	protected $table = 'transfer_stock';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'transfer_id' => 'int',
		'org_stock_id' => 'int',
		'new_stock_id' => 'int',
		'quantity' => 'int'
	];

	protected $fillable = [
		'transfer_id',
		'org_stock_id',
		'new_stock_id',
		'quantity'
	];

	public function stock()
	{
		return $this->belongsTo(\App\Models\Stock::class, 'new_stock_id');
	}

	public function transfer()
	{
		return $this->belongsTo(\App\Models\Transfer::class);
	}
}
