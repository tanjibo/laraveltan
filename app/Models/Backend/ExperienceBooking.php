<?php

/**
 * Created by Reliese Model.
 * Date: Wed, 06 Dec 2017 15:59:04 +0800.
 */

namespace App\Models\Backend;

use App\Events\SendNotificationEvent;
use Illuminate\Http\Request;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * Class ExperienceBooking
 *
 * @property int $id
 * @property int $user_id
 * @property \Carbon\Carbon $checkin
 * @property \Carbon\Carbon $checkout
 * @property int $price
 * @property int $balance
 * @property int $real_price
 * @property int $pay_mode
 * @property string $customer
 * @property int $gender
 * @property string $mobile
 * @property int $people
 * @property string $requirements
 * @property string $remark
 * @property int $status
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * @property int $is_comment
 * @property string $partner
 * @property string $source
 *
 * @property \App\Models\User $user
 * @property \Illuminate\Database\Eloquent\Collection $experience_booking_rooms
 *
 * @package App\Models
 */
class ExperienceBooking extends \App\Models\ExperienceBooking
{
    use LogsActivity;
    use \Illuminate\Database\Eloquent\SoftDeletes;




    public function experience_booking_rooms()
    {
        return $this->belongsToMany(ExperienceRoom::class, 'experience_booking_room', 'experience_booking_id', 'experience_room_id');
    }

    public function experience_booking_requirements()
    {
        return $this->hasMany(ExperienceBookingRequirement::class, 'booking_id');
    }


    /**
     * @param $id
     * @param $status
     * @return bool
     * 更改订单状态
     */
    public static function changeStatus( ExperienceBooking $booking )
    {
        if (!$booking)
            return false;

        //删除订单
        if (request()->status == static::STATUS_DEL) {
            $booking->delete();
            return true;
        }

        if ($booking->status == request()->status) {
            return true;
        }


        $booking->status = request()->status;
        return $booking->save();
    }


    /**
     * @param Request $request
     * @return
     * 数据处理放在观察者 ExperienceRoomBookingObserver 里
     */
    public static function store( Request $request )
    {

        if ($model = static::query()->create($request->all())) {
            $model->experience_booking_rooms()->attach($request->rooms);
            event(new SendNotificationEvent($model));
            return $model;
        }

        return false;
    }


    /**
     * @param $model
     * @return bool
     * 修改订单
     */
    public static function modify( ExperienceBooking $booking )
    {

        if ($saved = $booking->fill(request()->all())->save()) {
            $booking->experience_booking_rooms()->sync(request()->rooms);
        }
        return $saved ?: false;
    }


}
