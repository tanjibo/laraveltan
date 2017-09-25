<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 18 Sep 2017 15:10:45 +0800.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Cart
 * 
 * @property int $user_id
 * @property int $item_id
 * @property int $type
 * @property int $quantity
 * @property \Carbon\Carbon $created_at
 * 
 * @property \App\Models\User $user
 *
 * @package App\Models
 */
class Cart extends Eloquent
{
	protected $table = 'cart';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'user_id' => 'int',
		'item_id' => 'int',
		'type' => 'int',
		'quantity' => 'int'
	];

	protected $fillable = [
		'user_id',
		'item_id',
		'type',
		'quantity'
	];

	public function user()
	{
		return $this->belongsTo(\App\Models\User::class);
	}
}
