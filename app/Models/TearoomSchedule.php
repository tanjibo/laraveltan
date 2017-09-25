<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 18 Sep 2017 15:10:45 +0800.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class TearoomSchedule
 * 
 * @property int $tearoom_id
 * @property \Carbon\Carbon $date
 * @property string $points
 * 
 * @property \App\Models\Tearoom $tearoom
 *
 * @package App\Models
 */
class TearoomSchedule extends Eloquent
{
	protected $table = 'tearoom_schedule';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'tearoom_id' => 'int'
	];

	protected $dates = [
		'date'
	];

	protected $fillable = [
		'tearoom_id',
		'date',
		'points'
	];

	public function tearoom()
	{
		return $this->belongsTo(\App\Models\Tearoom::class);
	}
}
