<?php

/**
 * Created by Reliese Model.
 * Date: Wed, 06 Dec 2017 15:59:04 +0800.
 */

namespace App\Models\Backend;


use App\Models\TearoomBookingRequirement;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * Class TearoomBooking
 *
 * @property int $id
 * @property int $user_id
 * @property int $tearoom_id
 * @property \Carbon\Carbon $date
 * @property string $time
 * @property int $start_point
 * @property int $end_point
 * @property string $customer
 * @property int $gender
 * @property string $mobile
 * @property int $peoples
 * @property int $fee
 * @property int $discount
 * @property int $real_fee
 * @property int $pay_mode
 * @property string $tips
 * @property string $remark
 * @property int $status
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 *
 * @property \App\Models\Tearoom $tearoom
 * @property \App\Models\User $user
 *
 * @package App\Models
 */
class TearoomBooking extends \App\Models\TearoomBooking
{

    use LogsActivity;





    public function tearoom_booking_requirements()
    {
        return $this->hasMany(TearoomBookingRequirement::class, 'booking_id');
    }


    public static function changeStatus( TearoomBooking $booking )
    {
        if (!$booking)
            return false;

        //删除订单
//        if (request()->status == static::STATUS_CANCEL) {
//            $booking->delete();
//            return true;
//        }

        if ($booking->status == request()->status) {
            return true;
        }


        $booking->status = request()->status;
        return $booking->save();
    }



}
