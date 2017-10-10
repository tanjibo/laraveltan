<?php

namespace App\Http\Resources\Front;

use App\Models\ExperienceRoom;
use Illuminate\Http\Resources\Json\Resource;

class ExperienceRoomBookingSanResource extends Resource
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
        return $this->common() + [
                'date'         => $this->date->toDateString(),
                'time_quantum' => $this->time_quantum,
                'fee'          => $this->fee,
                'real_fee'     => $this->real_fee,
                'type'         => $this->type,
                'room'         => ExperienceRoom::query()->where('id', $this->room_id)->value('name'),
            ];
    }
}
