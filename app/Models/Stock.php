<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 18 Sep 2017 15:10:45 +0800.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Stock
 * 
 * @property int $id
 * @property int $product_id
 * @property int $warehouse_id
 * @property int $warehouse_space_id
 * @property int $total
 * @property int $quantity
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * 
 * @property \App\Models\Product $product
 * @property \App\Models\Warehouse $warehouse
 * @property \App\Models\StockLog $stock_log
 * @property \Illuminate\Database\Eloquent\Collection $transfers
 *
 * @package App\Models
 */
class Stock extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;
	protected $table = 'stock';

	protected $casts = [
		'product_id' => 'int',
		'warehouse_id' => 'int',
		'warehouse_space_id' => 'int',
		'total' => 'int',
		'quantity' => 'int'
	];

	protected $fillable = [
		'product_id',
		'warehouse_id',
		'warehouse_space_id',
		'total',
		'quantity'
	];

	public function product()
	{
		return $this->belongsTo(\App\Models\Product::class);
	}

	public function warehouse()
	{
		return $this->belongsTo(\App\Models\Warehouse::class);
	}

	public function stock_log()
	{
		return $this->hasOne(\App\Models\StockLog::class);
	}

	public function transfers()
	{
		return $this->belongsToMany(\App\Models\Transfer::class, 'transfer_stock', 'new_stock_id')
					->withPivot('org_stock_id', 'quantity');
	}
}
