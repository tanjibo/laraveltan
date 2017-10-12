<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 18 Sep 2017 15:10:45 +0800.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class ExperienceSpecialRoomTimePoint
 * 
 * @property int $id
 * @property int $room_id
 * @property int $time_point
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 *
 * @package App\Models
 */
class ExperienceSpecialRoomTimePoint extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;
	protected $table = 'experience_special_room_time_point';

	protected $casts = [
		'room_id' => 'int',
		'time_point' => 'int'
	];

	protected $fillable = [
		'room_id',
		'time_point'
	];
    public function schedule(){
        return $this->hasMany(ExperienceSpecialRoomSchedule::class,'schedule_id','id');
    }
}
