<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 18 Sep 2017 15:10:45 +0800.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class ComboProduct
 * 
 * @property int $combo_id
 * @property int $product_id
 * @property int $quantity
 * 
 * @property \App\Models\Combo $combo
 * @property \App\Models\Product $product
 *
 * @package App\Models
 */
class ComboProduct extends Eloquent
{
	protected $table = 'combo_product';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'combo_id' => 'int',
		'product_id' => 'int',
		'quantity' => 'int'
	];

	protected $fillable = [
		'quantity'
	];

	public function combo()
	{
		return $this->belongsTo(\App\Models\Combo::class);
	}

	public function product()
	{
		return $this->belongsTo(\App\Models\Product::class);
	}
}
