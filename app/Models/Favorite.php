<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 18 Sep 2017 15:10:45 +0800.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Favorite
 * 
 * @property int $id
 * @property int $user_id
 * @property int $item_id
 * @property int $type
 * @property \Carbon\Carbon $created_at
 * 
 * @property \App\Models\User $user
 *
 * @package App\Models
 */
class Favorite extends Eloquent
{
	public $timestamps = false;

	protected $casts = [
		'user_id' => 'int',
		'item_id' => 'int',
		'type' => 'int'
	];

	protected $fillable = [
		'user_id',
		'item_id',
		'type'
	];

	public function user()
	{
		return $this->belongsTo(\App\Models\User::class);
	}
}
