<?php

namespace App\Http\Controllers;

use App\Api\ExperienceBooking;
use App\Api\ExperienceSpecialBooking;
use App\Api\ExperienceSpecialBookingXin;
use Carbon\Carbon;
use Illuminate\Http\Request;

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

        $data   = ExperienceBooking::where([['created_at', '<=', $before],['status',0],['pay_mode','!=',ExperienceBooking::PAY_MODE_OFFLINE]])->orderBy('created_at', 'desc')->get();

        if(count($data->toArray())){
              foreach($data as $v){
                  ExperienceBooking::changeStatus($v->id,-10);
                  \Log::info('',$v->toArray());
              }
           }


    }

    public function canBookingSpecial(){
        $date   = new Carbon('now');
        $before = $date->addHours(8)->subMinutes(1);
        $data   = ExperienceSpecialBooking::where('created_at', '<=', $before)->where('status', 0)->orderBy('created_at', 'desc')->first();
    }

    public function canBookingSpecialXing(){
        $date   = new Carbon('now');
        $before = $date->addHours(8)->subMinutes(1);
        $data   = ExperienceSpecialBookingXin::where('created_at', '<=', $before)->where('status', 0)->orderBy('created_at', 'desc')->first();
    }
}
