<?php

namespace App\Http\Resources\Front;

use Illuminate\Http\Resources\Json\Resource;

class ExperienceRoomBookingResource extends Resource
{
    use ExperienceRoomBookingTrait;
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray( $request )
    {
       return $this->common()+[
            'checkin'      => $this->checkin->toDateString(),
            'checkout'     => $this->checkout->toDateString(),
            'rooms'        => ExperienceRoomResource::collection($this->rooms),
            'price'        => $this->price,
            'real_price'   => $this->real_price,
            'type'         => 1, //默认为这个好了
        ];

    }
}
