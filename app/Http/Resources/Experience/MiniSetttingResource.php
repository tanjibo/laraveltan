<?php

namespace App\Http\Resources\Experience;

use Illuminate\Http\Resources\Json\Resource;

class MiniSetttingResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'common_color'=>$this->common_color,
            'navigation_bar_color'=>$this->navigation_bar_color,
            //'banner_url'=>toHttps($this->banner_url),
            'banner_url'=>$this->banner_url,
        ];
    }
}
