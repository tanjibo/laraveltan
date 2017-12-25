<?php

namespace App\Http\Resources\Experience;

use Illuminate\Http\Resources\Json\Resource;

class ExperienceRoomSliderResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'url'=>$this->url.'?imageView2/q/70/interlace/1|imageslim'
        ];
    }
}
