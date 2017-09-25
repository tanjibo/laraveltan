<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 18 Sep 2017 15:10:45 +0800.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Type
 * 
 * @property int $id
 * @property string $name
 * @property string $remark
 * @property int $status
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * 
 * @property \Illuminate\Database\Eloquent\Collection $attributes
 * @property \Illuminate\Database\Eloquent\Collection $categories
 * @property \Illuminate\Database\Eloquent\Collection $products
 *
 * @package App\Models
 */
class Type extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;
	protected $table = 'type';

	protected $casts = [
		'status' => 'int'
	];

	protected $fillable = [
		'name',
		'remark',
		'status'
	];

	public function attributes()
	{
		return $this->hasMany(\App\Models\Attribute::class);
	}

	public function categories()
	{
		return $this->hasMany(\App\Models\Category::class);
	}

	public function products()
	{
		return $this->hasMany(\App\Models\Product::class);
	}
}
