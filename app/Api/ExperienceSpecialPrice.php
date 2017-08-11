<?php

namespace App\Api;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class ExperienceSpecialPrice extends Model
{
    protected $table="experience_special_price";

     public $timestamps = false;

    /**
     * 删除过期的时间
     */
     static public function delSpecialPriceOutDate(){
         $date   = (new Carbon('now'))->toDateString();
         $count=self::where('date', '<', $date)->count();
         if($count>0){
             self::where('date', '<', $date)->orderBy('date','desc')->delete();
         }
     }

}
