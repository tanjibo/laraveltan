<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 18 Sep 2017 15:10:45 +0800.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class ExperienceRoomCommonSetting
 * 
 * @property int $id
 * @property string $url
 * @property string $system_tip
 * @property string $type
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * @property string $name
 *
 * @package App\Models
 */
class ExperienceRoomCommonSetting extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;
	protected $table = 'experience_room_common_setting';

	protected $fillable = [
		'url',
		'system_tip',
		'type',
		'name'
	];
}
