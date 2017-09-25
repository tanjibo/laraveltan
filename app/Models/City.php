<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 18 Sep 2017 15:10:45 +0800.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class City
 * 
 * @property int $id
 * @property string $name
 * @property int $pid
 * @property int $sort
 * @property int $status
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * 
 * @property \Illuminate\Database\Eloquent\Collection $addresses
 * @property \Illuminate\Database\Eloquent\Collection $orders
 *
 * @package App\Models
 */
class City extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;
	protected $table = 'city';

	protected $casts = [
		'pid' => 'int',
		'sort' => 'int',
		'status' => 'int'
	];

	protected $fillable = [
		'name',
		'pid',
		'sort',
		'status'
	];

	public function addresses()
	{
		return $this->hasMany(\App\Models\Address::class);
	}

	public function orders()
	{
		return $this->hasMany(\App\Models\Order::class);
	}
}
