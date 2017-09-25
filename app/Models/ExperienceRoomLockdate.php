<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 18 Sep 2017 15:10:45 +0800.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class ExperienceRoomLockdate
 * 
 * @property int $id
 * @property int $room_id
 * @property string $lockdate
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 *
 * @package App\Models
 */
class ExperienceRoomLockdate extends Eloquent
{
	protected $table = 'experience_room_lockdate';

	protected $casts = [
		'room_id' => 'int'
	];

	protected $fillable = [
		'room_id',
		'lockdate'
	];
}
