<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 18 Sep 2017 15:10:45 +0800.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class WalletRecharge
 * 
 * @property int $id
 * @property int $user_id
 * @property int $fee
 * @property int $status
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * 
 * @property \App\Models\User $user
 *
 * @package App\Models
 */
class WalletRecharge extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;
	protected $table = 'wallet_recharge';

	protected $casts = [
		'user_id' => 'int',
		'fee' => 'int',
		'status' => 'int'
	];

	protected $fillable = [
		'user_id',
		'fee',
		'status'
	];

	public function user()
	{
		return $this->belongsTo(\App\Models\User::class);
	}
}
