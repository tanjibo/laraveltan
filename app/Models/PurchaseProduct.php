<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 18 Sep 2017 15:10:45 +0800.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class PurchaseProduct
 * 
 * @property int $purchase_id
 * @property int $product_id
 * @property int $unit_price
 * @property int $quantity
 * 
 * @property \App\Models\Product $product
 * @property \App\Models\Purchase $purchase
 *
 * @package App\Models
 */
class PurchaseProduct extends Eloquent
{
	protected $table = 'purchase_product';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'purchase_id' => 'int',
		'product_id' => 'int',
		'unit_price' => 'int',
		'quantity' => 'int'
	];

	protected $fillable = [
		'unit_price',
		'quantity'
	];

	public function product()
	{
		return $this->belongsTo(\App\Models\Product::class);
	}

	public function purchase()
	{
		return $this->belongsTo(\App\Models\Purchase::class);
	}
}
