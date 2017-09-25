<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 18 Sep 2017 15:10:45 +0800.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class SignIn
 * 
 * @property int $user_id
 * @property \Carbon\Carbon $time
 * @property int $continuous
 * 
 * @property \App\Models\User $user
 *
 * @package App\Models
 */
class SignIn extends Eloquent
{
	protected $table = 'sign_in';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'user_id' => 'int',
		'continuous' => 'int'
	];

	protected $dates = [
		'time'
	];

	protected $fillable = [
		'user_id',
		'time',
		'continuous'
	];

	public function user()
	{
		return $this->belongsTo(\App\Models\User::class);
	}
}
