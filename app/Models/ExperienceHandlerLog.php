<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 18 Sep 2017 15:10:45 +0800.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class ExperienceHandlerLog
 * 
 * @property int $id
 * @property int $type
 * @property int $experience_booking_id
 * @property string $handler
 * @property int $experience_room_id
 * @property string $event
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 *
 * @package App\Models
 */
class ExperienceHandlerLog extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;
	protected $table = 'experience_handler_log';

	protected $casts = [
		'type' => 'int',
		'experience_booking_id' => 'int',
		'experience_room_id' => 'int'
	];

	protected $fillable = [
		'type',
		'experience_booking_id',
		'handler',
		'experience_room_id',
		'event'
	];
}
