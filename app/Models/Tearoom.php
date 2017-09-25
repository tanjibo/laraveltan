<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 18 Sep 2017 15:10:45 +0800.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Tearoom
 * 
 * @property int $id
 * @property string $name
 * @property int $limits
 * @property int $start_point
 * @property int $end_point
 * @property string $image
 * @property int $sort
 * @property int $type
 * @property int $status
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * 
 * @property \Illuminate\Database\Eloquent\Collection $tearoom_bookings
 * @property \Illuminate\Database\Eloquent\Collection $tearoom_prices
 * @property \App\Models\TearoomSchedule $tearoom_schedule
 *
 * @package App\Models
 */
class Tearoom extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;
	protected $table = 'tearoom';

	protected $casts = [
		'limits' => 'int',
		'start_point' => 'int',
		'end_point' => 'int',
		'sort' => 'int',
		'type' => 'int',
		'status' => 'int'
	];

	protected $fillable = [
		'name',
		'limits',
		'start_point',
		'end_point',
		'image',
		'sort',
		'type',
		'status'
	];

	public function tearoom_bookings()
	{
		return $this->hasMany(\App\Models\TearoomBooking::class);
	}

	public function tearoom_prices()
	{
		return $this->hasMany(\App\Models\TearoomPrice::class);
	}

	public function tearoom_schedule()
	{
		return $this->hasOne(\App\Models\TearoomSchedule::class);
	}
}
