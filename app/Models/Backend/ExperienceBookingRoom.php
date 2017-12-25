<?php

/**
 * Created by Reliese Model.
 * Date: Wed, 06 Dec 2017 15:59:04 +0800.
 */

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class ExperienceBookingRoom
 * 
 * @property int $experience_booking_id
 * @property int $experience_room_id
 * 
 * @property \App\Models\ExperienceBooking $experience_booking
 * @property \App\Models\ExperienceRoom $experience_room
 *
 * @package App\Models
 */
class ExperienceBookingRoom extends Eloquent
{
	protected $table = 'experience_booking_room';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'experience_booking_id' => 'int',
		'experience_room_id' => 'int'
	];

	public function experience_booking()
	{
		return $this->belongsTo(\App\Models\ExperienceBooking::class);
	}

	public function experience_room()
	{
		return $this->belongsTo(\App\Models\ExperienceRoom::class);
	}
}
