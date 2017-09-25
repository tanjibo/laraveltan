<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 18 Sep 2017 15:10:45 +0800.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class ExperienceRoomDetail
 * 
 * @property int $id
 * @property int $room_id
 * @property string $url
 * @property string $content
 *
 * @package App\Models
 */
class ExperienceRoomDetail extends Eloquent
{
	protected $table = 'experience_room_detail';
	public $timestamps = false;

	protected $casts = [
		'room_id' => 'int'
	];

	protected $fillable = [
		'room_id',
		'url',
		'content'
	];
}
