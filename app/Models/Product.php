<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 18 Sep 2017 15:10:45 +0800.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Product
 * 
 * @property int $id
 * @property int $type_id
 * @property int $category_id
 * @property int $supplier_id
 * @property string $number
 * @property string $name
 * @property string $title
 * @property string $summary
 * @property int $sell_price
 * @property int $min_price
 * @property int $cost_price
 * @property string $cover
 * @property string $image
 * @property string $picture
 * @property string $sticker
 * @property int $stock
 * @property int $has_3d
 * @property int $visit_volume
 * @property int $sales_volume
 * @property int $status
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * 
 * @property \App\Models\Category $category
 * @property \App\Models\Type $type
 * @property \Illuminate\Database\Eloquent\Collection $cargos
 * @property \Illuminate\Database\Eloquent\Collection $combos
 * @property \Illuminate\Database\Eloquent\Collection $entry_cargos
 * @property \Illuminate\Database\Eloquent\Collection $attributes
 * @property \App\Models\ProductDetail $product_detail
 * @property \Illuminate\Database\Eloquent\Collection $specs
 * @property \Illuminate\Database\Eloquent\Collection $purchases
 * @property \Illuminate\Database\Eloquent\Collection $stocks
 *
 * @package App\Models
 */
class Product extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;
	protected $table = 'product';

	protected $casts = [
		'type_id' => 'int',
		'category_id' => 'int',
		'supplier_id' => 'int',
		'sell_price' => 'int',
		'min_price' => 'int',
		'cost_price' => 'int',
		'stock' => 'int',
		'has_3d' => 'int',
		'visit_volume' => 'int',
		'sales_volume' => 'int',
		'status' => 'int'
	];

	protected $fillable = [
		'type_id',
		'category_id',
		'supplier_id',
		'number',
		'name',
		'title',
		'summary',
		'sell_price',
		'min_price',
		'cost_price',
		'cover',
		'image',
		'picture',
		'sticker',
		'stock',
		'has_3d',
		'visit_volume',
		'sales_volume',
		'status'
	];

	public function category()
	{
		return $this->belongsTo(\App\Models\Category::class);
	}

	public function type()
	{
		return $this->belongsTo(\App\Models\Type::class);
	}

	public function cargos()
	{
		return $this->hasMany(\App\Models\Cargo::class);
	}

	public function combos()
	{
		return $this->belongsToMany(\App\Models\Combo::class)
					->withPivot('quantity');
	}

	public function entry_cargos()
	{
		return $this->hasMany(\App\Models\EntryCargo::class);
	}

	public function attributes()
	{
		return $this->belongsToMany(\App\Models\Attribute::class, 'product_attribute')
					->withPivot('value');
	}

	public function product_detail()
	{
		return $this->hasOne(\App\Models\ProductDetail::class);
	}

	public function specs()
	{
		return $this->belongsToMany(\App\Models\Spec::class)
					->withPivot('value', 'sort');
	}

	public function purchases()
	{
		return $this->belongsToMany(\App\Models\Purchase::class, 'purchase_product')
					->withPivot('unit_price', 'quantity');
	}

	public function stocks()
	{
		return $this->hasMany(\App\Models\Stock::class);
	}
}
