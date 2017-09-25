<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 18 Sep 2017 15:10:45 +0800.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class H5TeagiftShare
 * 
 * @property int $teagift_id
 * @property string $consignee
 * @property string $mobile
 * @property string $address
 * @property string $openid
 * @property string $username
 * @property string $avatar
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property \App\Models\H5Teagift $h5_teagift
 *
 * @package App\Models
 */
class H5TeagiftShare extends Eloquent
{
	protected $table = 'h5_teagift_share';
	public $incrementing = false;

	protected $casts = [
		'teagift_id' => 'int'
	];

	protected $fillable = [
		'teagift_id',
		'consignee',
		'mobile',
		'address',
		'openid',
		'username',
		'avatar'
	];

	public function h5_teagift()
	{
		return $this->belongsTo(\App\Models\H5Teagift::class, 'teagift_id');
	}
}
