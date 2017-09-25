<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 18 Sep 2017 15:10:45 +0800.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class AccountRecord
 * 
 * @property int $id
 * @property int $user_id
 * @property int $amount
 * @property int $type
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * 
 * @property \App\Models\User $user
 *
 * @package App\Models
 */
class AccountRecord extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;

	protected $casts = [
		'user_id' => 'int',
		'amount' => 'int',
		'type' => 'int'
	];

	protected $fillable = [
		'user_id',
		'amount',
		'type'
	];

	public function user()
	{
		return $this->belongsTo(\App\Models\User::class);
	}
}
