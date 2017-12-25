<?php

/**
 * Created by Reliese Model.
 * Date: Wed, 06 Dec 2017 15:59:04 +0800.
 */

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class TearoomBookingRequirement
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
class TearoomBookingRequirement extends Eloquent
{
	use \Illuminate\Database\Eloquent\SoftDeletes;

	protected $casts = [
		'booking_id' => 'int',
		'admin_id' => 'int',
		'type' => 'int'
	];

	protected $hidden = [
		'deleted_at'
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
