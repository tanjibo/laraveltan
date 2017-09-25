<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 18 Sep 2017 15:10:45 +0800.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class ProductAttribute
 * 
 * @property int $product_id
 * @property int $attribute_id
 * @property string $value
 * 
 * @property \App\Models\Attribute $attribute
 * @property \App\Models\Product $product
 *
 * @package App\Models
 */
class ProductAttribute extends Eloquent
{
	protected $table = 'product_attribute';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'product_id' => 'int',
		'attribute_id' => 'int'
	];

	protected $fillable = [
		'value'
	];

	public function attribute()
	{
		return $this->belongsTo(\App\Models\Attribute::class);
	}

	public function product()
	{
		return $this->belongsTo(\App\Models\Product::class);
	}
}
