<?php

namespace App\Http\Controllers;

use App\Api\ExperienceBooking;
use Carbon\Carbon;

class CancelOrderController extends Controller
{


     static public function canBookingNormal()
    {
        $date   = new Carbon('now');
          if(\App::environment()=='develop'){
              $before = $date->subMinutes(1);
          }else{
              $before=$date->subMinutes(10);
          }

        $data   = ExperienceBooking::query()->where([['created_at', '<=', $before],['status',0],['pay_mode','!=',ExperienceBooking::PAY_MODE_OFFLINE]])->orderBy('created_at', 'desc')->get();

        if(count($data->toArray())){
              foreach($data as $v){
                  ExperienceBooking::changeStatus($v->id,-10);
                  \Log::info('',$v->toArray());
              }
           }


    }


}
