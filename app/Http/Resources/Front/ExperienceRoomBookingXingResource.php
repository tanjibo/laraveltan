<?php

namespace App\Http\Resources\Front;

use App\Api\ExperienceRoom;
use Illuminate\Http\Resources\Json\Resource;

class ExperienceRoomBookingXingResource extends Resource
{
    use ExperienceRoomBookingTrait;
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
       return $this->common()+[
             'date'=>$this->date->toDateString(),
             'price'=>$this->price,
             'real_price'=>$this->real_price,
             'room'=>ExperienceRoom::query()->where('id',$this->room_id)->value('name'),
             'type'=>$this->type
           ];
    }
}
