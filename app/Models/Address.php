<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 18 Sep 2017 15:10:45 +0800.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Address
 * 
 * @property int $id
 * @property int $user_id
 * @property int $province_id
 * @property int $city_id
 * @property string $consignee
 * @property string $mobile
 * @property string $detail
 * @property int $default
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * 
 * @property \App\Models\City $city
 * @property \App\Models\User $user
 *
 * @package App\Models
 */
class Address extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;
	protected $table = 'address';

	protected $casts = [
		'user_id' => 'int',
		'province_id' => 'int',
		'city_id' => 'int',
		'default' => 'int'
	];

	protected $fillable = [
		'user_id',
		'province_id',
		'city_id',
		'consignee',
		'mobile',
		'detail',
		'default'
	];

	public function city()
	{
		return $this->belongsTo(\App\Models\City::class);
	}

	public function user()
	{
		return $this->belongsTo(\App\Models\User::class);
	}
}
