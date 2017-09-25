<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 18 Sep 2017 15:10:45 +0800.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Cargo
 * 
 * @property int $id
 * @property int $purchase_id
 * @property int $product_id
 * @property string $batch_number
 * @property int $total
 * @property int $quantity
 * @property int $status
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * 
 * @property \App\Models\Product $product
 * @property \App\Models\Purchase $purchase
 * @property \Illuminate\Database\Eloquent\Collection $entries
 *
 * @package App\Models
 */
class Cargo extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;
	protected $table = 'cargo';

	protected $casts = [
		'purchase_id' => 'int',
		'product_id' => 'int',
		'total' => 'int',
		'quantity' => 'int',
		'status' => 'int'
	];

	protected $fillable = [
		'purchase_id',
		'product_id',
		'batch_number',
		'total',
		'quantity',
		'status'
	];

	public function product()
	{
		return $this->belongsTo(\App\Models\Product::class);
	}

	public function purchase()
	{
		return $this->belongsTo(\App\Models\Purchase::class);
	}

	public function entries()
	{
		return $this->belongsToMany(\App\Models\Entry::class, 'entry_cargo')
					->withPivot('product_id', 'warehouse_space_id', 'quantity');
	}
}
