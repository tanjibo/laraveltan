<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 18 Sep 2017 15:10:45 +0800.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Attribute
 * 
 * @property int $id
 * @property int $type_id
 * @property string $name
 * @property string $options
 * @property int $sort
 * @property int $status
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * 
 * @property \App\Models\Type $type
 * @property \Illuminate\Database\Eloquent\Collection $products
 *
 * @package App\Models
 */
class Attribute extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;
	protected $table = 'attribute';

	protected $casts = [
		'type_id' => 'int',
		'sort' => 'int',
		'status' => 'int'
	];

	protected $fillable = [
		'type_id',
		'name',
		'options',
		'sort',
		'status'
	];

	public function type()
	{
		return $this->belongsTo(\App\Models\Type::class);
	}

	public function products()
	{
		return $this->belongsToMany(\App\Models\Product::class, 'product_attribute')
					->withPivot('value');
	}
}
