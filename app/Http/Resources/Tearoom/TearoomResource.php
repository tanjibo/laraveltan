<?php

namespace App\Http\Resources\Tearoom;

use Illuminate\Http\Resources\Json\Resource;

class TearoomResource extends Resource
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
            'id'=>$this->id,
            'image'=>$this->image,
            'limits'=>$this->limits,
            'name'=>$this->name
        ];
    }
}
