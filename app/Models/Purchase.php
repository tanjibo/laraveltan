<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 18 Sep 2017 15:10:45 +0800.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Purchase
 * 
 * @property int $id
 * @property string $number
 * @property int $fee
 * @property string $handler
 * @property \Carbon\Carbon $purchase_at
 * @property \Carbon\Carbon $received_at
 * @property string $receiver
 * @property string $remark
 * @property int $status
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * 
 * @property \Illuminate\Database\Eloquent\Collection $cargos
 * @property \Illuminate\Database\Eloquent\Collection $products
 *
 * @package App\Models
 */
class Purchase extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;
	protected $table = 'purchase';

	protected $casts = [
		'fee' => 'int',
		'status' => 'int'
	];

	protected $dates = [
		'purchase_at',
		'received_at'
	];

	protected $fillable = [
		'number',
		'fee',
		'handler',
		'purchase_at',
		'received_at',
		'receiver',
		'remark',
		'status'
	];

	public function cargos()
	{
		return $this->hasMany(\App\Models\Cargo::class);
	}

	public function products()
	{
		return $this->belongsToMany(\App\Models\Product::class, 'purchase_product')
					->withPivot('unit_price', 'quantity');
	}
}
