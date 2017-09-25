<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 18 Sep 2017 15:10:45 +0800.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class ExperienceSpecialRoomRestTime
 * 
 * @property int $id
 * @property int $room_id
 * @property string $am_rest_start_time
 * @property string $am_rest_end_time
 * @property string $pm_rest_start_time
 * @property string $pm_rest_end_time
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 *
 * @package App\Models
 */
class ExperienceSpecialRoomRestTime extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;
	protected $table = 'experience_special_room_rest_time';

	protected $casts = [
		'room_id' => 'int'
	];

	protected $fillable = [
		'room_id',
		'am_rest_start_time',
		'am_rest_end_time',
		'pm_rest_start_time',
		'pm_rest_end_time'
	];
}
