<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 18 Sep 2017 15:10:45 +0800.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class ProductSpec
 * 
 * @property int $product_id
 * @property int $spec_id
 * @property string $value
 * @property int $sort
 * 
 * @property \App\Models\Product $product
 * @property \App\Models\Spec $spec
 *
 * @package App\Models
 */
class ProductSpec extends Eloquent
{
	protected $table = 'product_spec';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'product_id' => 'int',
		'spec_id' => 'int',
		'sort' => 'int'
	];

	protected $fillable = [
		'value',
		'sort'
	];

	public function product()
	{
		return $this->belongsTo(\App\Models\Product::class);
	}

	public function spec()
	{
		return $this->belongsTo(\App\Models\Spec::class);
	}
}
