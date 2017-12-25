<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 18 Sep 2017 15:10:45 +0800.
 */

namespace App\Models\Api;


/**
 * Class ExperienceRoom
 *
 * @property int $id
 * @property string $name
 * @property int $price
 * @property string $intro
 * @property string $cover
 * @property int $type
 * @property string $attach
 * @property int $sort
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * @property string $attach_url
 * @property string $design_concept
 *
 * @property \Illuminate\Database\Eloquent\Collection $experience_booking_rooms
 * @property \App\Models\ExperienceSpecialPrice $experience_special_price
 *
 * @package App\Models
 */
class ExperienceRoom extends \App\Models\ExperienceRoom
{



}
