<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 18 Sep 2017 15:10:45 +0800.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Combo
 * 
 * @property int $id
 * @property string $name
 * @property string $summary
 * @property int $cost_price
 * @property int $org_price
 * @property int $sell_price
 * @property int $stock
 * @property string $cover
 * @property int $type
 * @property int $visit_volume
 * @property int $sales_volume
 * @property int $status
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * 
 * @property \App\Models\ComboDetail $combo_detail
 * @property \Illuminate\Database\Eloquent\Collection $products
 *
 * @package App\Models
 */
class Combo extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;
	protected $table = 'combo';

	protected $casts = [
		'cost_price' => 'int',
		'org_price' => 'int',
		'sell_price' => 'int',
		'stock' => 'int',
		'type' => 'int',
		'visit_volume' => 'int',
		'sales_volume' => 'int',
		'status' => 'int'
	];

	protected $fillable = [
		'name',
		'summary',
		'cost_price',
		'org_price',
		'sell_price',
		'stock',
		'cover',
		'type',
		'visit_volume',
		'sales_volume',
		'status'
	];

	public function combo_detail()
	{
		return $this->hasOne(\App\Models\ComboDetail::class);
	}

	public function products()
	{
		return $this->belongsToMany(\App\Models\Product::class)
					->withPivot('quantity');
	}
}
