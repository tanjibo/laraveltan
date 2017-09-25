<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 18 Sep 2017 15:10:45 +0800.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class H5Teagift
 * 
 * @property int $id
 * @property string $owner
 * @property int $fee
 * @property int $status
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \App\Models\H5TeagiftShare $h5_teagift_share
 *
 * @package App\Models
 */
class H5Teagift extends Eloquent
{
	protected $table = 'h5_teagift';

	protected $casts = [
		'fee' => 'int',
		'status' => 'int'
	];

	protected $fillable = [
		'owner',
		'fee',
		'status'
	];

	public function h5_teagift_share()
	{
		return $this->hasOne(\App\Models\H5TeagiftShare::class, 'teagift_id');
	}
}
