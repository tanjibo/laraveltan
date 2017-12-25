<?php

/**
 * Created by Reliese Model.
 * Date: Wed, 06 Dec 2017 15:59:04 +0800.
 */

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Spatie\Activitylog\Traits\LogsActivity;

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
 *
 * @package App\Models
 */
class ExperienceRoom extends \App\Models\ExperienceRoom
{
    use \Illuminate\Database\Eloquent\SoftDeletes;

    use LogsActivity;



    public function experience_booking_rooms()
    {
        return $this->hasMany(\App\Models\ExperienceBookingRoom::class);
    }

    public function sliders()
    {
        return $this->hasMany(ExperienceRoomSlider::class, 'room_id');
    }

    public function experience_special_price()
    {
        return $this->hasMany(ExperienceSpecialPrice::class);
    }

    public function scopeActive( $query )
    {
        return $query->whereIn('type', [ 1, 2 ]);
    }

    public function experience_room_bookings()
    {
        return $this->belongsToMany(ExperienceBooking::class, 'experience_booking_room', 'experience_room_id', 'experience_booking_id');
    }

    /**
     * 获得此视频的所有评论。
     */
    public function comments()
    {
        return $this->morphMany(ExperienceBookingComment::class, 'commentable');
    }


}
