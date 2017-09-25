<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 18 Sep 2017 15:10:45 +0800.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class CreditLog
 * 
 * @property int $user_id
 * @property int $credit
 * @property string $event
 * @property \Carbon\Carbon $created_at
 * 
 * @property \App\Models\User $user
 *
 * @package App\Models
 */
class CreditLog extends Eloquent
{
	protected $table = 'credit_log';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'user_id' => 'int',
		'credit' => 'int'
	];

	protected $fillable = [
		'user_id',
		'credit',
		'event'
	];

	public function user()
	{
		return $this->belongsTo(\App\Models\User::class);
	}
}
