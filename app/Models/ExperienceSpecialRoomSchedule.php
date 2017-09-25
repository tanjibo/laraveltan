<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 18 Sep 2017 15:10:45 +0800.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class ExperienceSpecialRoomSchedule
 * 
 * @property int $id
 * @property string $time_quantum
 * @property int $schedule_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * @property string $start_time
 * @property string $end_time
 * @property string $dot
 *
 * @package App\Models
 */
class ExperienceSpecialRoomSchedule extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;
	protected $table = 'experience_special_room_schedule';

	protected $casts = [
		'schedule_id' => 'int'
	];

	protected $fillable = [
		'time_quantum',
		'schedule_id',
		'start_time',
		'end_time',
		'dot'
	];
}
