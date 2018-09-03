<?php
/**
 * |--------------------------------------------------------------------------
 * |
 * |--------------------------------------------------------------------------
 * Created by PhpStorm.
 * User: weaving
 * Date: 7/8/2018
 * Time: 10:26 AM
 */

namespace Repositories;


use App\Http\Resources\Tearoom\BookingResource;
use App\Models\TearoomBooking;

use Illuminate\Support\Facades\Auth;

class TearoomBookingRepository
{

    public function orderListApi($orderStatus=''){
         //正常订单
        $model = TearoomBooking::query()->where('user_id', Auth::id())->when(
            $orderStatus!=100, function( $query ) use ($orderStatus) {
                $query->where('status',$orderStatus);
        }
        )->orderBy('created_at', 'desc')->get();

       return BookingResource::collection($model);
    }

}