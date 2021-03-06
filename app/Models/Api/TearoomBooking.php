<?php

/**
 * Created by Reliese Model.
 * Date: Mon, 18 Sep 2017 15:10:45 +0800.
 */

namespace App\Models\Api;



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

    public static function store($data){
      return   static::query()->create($data);
    }


    public static function changeOrderStatusToApi(\App\Models\Api\TearoomBooking $booking,$status='')
    {

        if (!$booking)
            return false;

        //删除订单
        if ($status == static::STATUS_DEL) {
            $booking->delete();
            return true;
        }

        if ($booking->status == $status) {
            return true;
        }


        $booking->status = $status;

        return $booking->save();
    }

}
