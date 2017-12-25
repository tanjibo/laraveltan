<?php

/**
 * Created by Reliese Model.
 * Date: Wed, 06 Dec 2017 17:06:34 +0800.
 */

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * Class ExperienceSpecialPrice
 * 
 * @property int $experience_room_id
 * @property \Carbon\Carbon $date
 * @property int $price
 * @property string $type
 * 
 * @property \App\Models\ExperienceRoom $experience_room
 *
 * @package App\Models
 */
class ExperienceSpecialPrice extends Eloquent
{
	protected $table = 'experience_special_price';
	public $incrementing = false;
	public $timestamps = false;
    use LogsActivity;
	protected $casts = [
		'experience_room_id' => 'int',
		'price' => 'int'
	];

	protected $dates = [
		'date'
	];

	protected $fillable = [
		'experience_room_id',
		'date',
		'price',
		'type'
	];

	public function experience_room()
	{
		return $this->belongsTo(\App\Models\ExperienceRoom::class);
	}
}
