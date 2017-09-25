<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 18 Sep 2017 15:10:45 +0800.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Spec
 * 
 * @property int $id
 * @property string $name
 * @property string $title
 * @property int $status
 * @property string $remark
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * 
 * @property \Illuminate\Database\Eloquent\Collection $products
 *
 * @package App\Models
 */
class Spec extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;
	protected $table = 'spec';

	protected $casts = [
		'status' => 'int'
	];

	protected $fillable = [
		'name',
		'title',
		'status',
		'remark'
	];

	public function products()
	{
		return $this->belongsToMany(\App\Models\Product::class)
					->withPivot('value', 'sort');
	}
}
