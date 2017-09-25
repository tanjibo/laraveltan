<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 18 Sep 2017 15:10:45 +0800.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class ProductDetail
 * 
 * @property int $product_id
 * @property int $type
 * @property int $level
 * @property string $unit
 * @property string $size
 * @property string $batch
 * @property string $images
 * @property string $poster
 * @property string $intro
 * @property string $remark
 * 
 * @property \App\Models\Product $product
 *
 * @package App\Models
 */
class ProductDetail extends Eloquent
{
	protected $table = 'product_detail';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'product_id' => 'int',
		'type' => 'int',
		'level' => 'int'
	];

	protected $fillable = [
		'product_id',
		'type',
		'level',
		'unit',
		'size',
		'batch',
		'images',
		'poster',
		'intro',
		'remark'
	];

	public function product()
	{
		return $this->belongsTo(\App\Models\Product::class);
	}
}
