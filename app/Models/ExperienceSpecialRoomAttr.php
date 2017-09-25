<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 18 Sep 2017 15:10:45 +0800.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class ExperienceSpecialRoomAttr
 * 
 * @property int $id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * @property int $status
 * @property string $intro
 * @property int $limit_people
 * @property string $bussiness_start_time
 * @property string $bussiness_end_time
 * @property int $room_id
 *
 * @package App\Models
 */
class ExperienceSpecialRoomAttr extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;
	protected $table = 'experience_special_room_attr';

	protected $casts = [
		'status' => 'int',
		'limit_people' => 'int',
		'room_id' => 'int'
	];

	protected $fillable = [
		'status',
		'intro',
		'limit_people',
		'bussiness_start_time',
		'bussiness_end_time',
		'room_id'
	];
}
