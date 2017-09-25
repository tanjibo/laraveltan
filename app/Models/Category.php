<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 18 Sep 2017 15:10:45 +0800.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Category
 * 
 * @property int $id
 * @property int $type_id
 * @property string $name
 * @property string $code
 * @property int $pid
 * @property string $cover
 * @property int $sort
 * @property int $visit_volume
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
class Category extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;
	protected $table = 'category';

	protected $casts = [
		'type_id' => 'int',
		'pid' => 'int',
		'sort' => 'int',
		'visit_volume' => 'int',
		'status' => 'int'
	];

	protected $fillable = [
		'type_id',
		'name',
		'code',
		'pid',
		'cover',
		'sort',
		'visit_volume',
		'status'
	];

	public function type()
	{
		return $this->belongsTo(\App\Models\Type::class);
	}

	public function products()
	{
		return $this->hasMany(\App\Models\Product::class);
	}
}
