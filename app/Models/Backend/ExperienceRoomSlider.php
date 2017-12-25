<?php

/**
 * Created by Reliese Model.
 * Date: Wed, 06 Dec 2017 15:59:04 +0800.
 */

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class ExperienceRoomSlider
 * 
 * @property int $id
 * @property int $room_id
 * @property string $url
 *
 * @package App\Models
 */
class ExperienceRoomSlider extends Eloquent
{
	protected $table = 'experience_room_slider';
	public $timestamps = false;

	protected $casts = [
		'room_id' => 'int'
	];

	protected $fillable = [
		'room_id',
		'url'
	];
}
