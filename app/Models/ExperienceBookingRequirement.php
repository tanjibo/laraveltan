<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 18 Sep 2017 15:10:45 +0800.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class ExperienceBookingRequirement
 * 
 * @property int $id
 * @property int $booking_id
 * @property int $admin_id
 * @property int $type
 * @property string $requirements
 * @property string $event
 * @property string $handler
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 *
 * @package App\Models
 */
class ExperienceBookingRequirement extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;

	protected $casts = [
		'booking_id' => 'int',
		'admin_id' => 'int',
		'type' => 'int'
	];

	protected $fillable = [
		'booking_id',
		'admin_id',
		'type',
		'requirements',
		'event',
		'handler'
	];
}
