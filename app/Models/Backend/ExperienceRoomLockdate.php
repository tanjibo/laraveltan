<?php

/**
 * Created by Reliese Model.
 * Date: Wed, 06 Dec 2017 15:59:04 +0800.
 */

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Spatie\Activitylog\Traits\LogsActivity;

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
   use LogsActivity;
	protected $casts = [
		'room_id' => 'int',
        'lockdate'=>'array'
	];

	protected $fillable = [
		'room_id',
		'lockdate'
	];
}
