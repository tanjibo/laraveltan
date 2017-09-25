<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 18 Sep 2017 15:10:45 +0800.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class EntryCargo
 * 
 * @property int $entry_id
 * @property int $cargo_id
 * @property int $product_id
 * @property int $warehouse_space_id
 * @property int $quantity
 * 
 * @property \App\Models\Cargo $cargo
 * @property \App\Models\Entry $entry
 * @property \App\Models\Product $product
 *
 * @package App\Models
 */
class EntryCargo extends Eloquent
{
	protected $table = 'entry_cargo';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'entry_id' => 'int',
		'cargo_id' => 'int',
		'product_id' => 'int',
		'warehouse_space_id' => 'int',
		'quantity' => 'int'
	];

	protected $fillable = [
		'product_id',
		'warehouse_space_id',
		'quantity'
	];

	public function cargo()
	{
		return $this->belongsTo(\App\Models\Cargo::class);
	}

	public function entry()
	{
		return $this->belongsTo(\App\Models\Entry::class);
	}

	public function product()
	{
		return $this->belongsTo(\App\Models\Product::class);
	}
}
