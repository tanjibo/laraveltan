<?php

namespace App\Http\Resources\Tearoom;

use Illuminate\Http\Resources\Json\Resource;

class BookingResource extends Resource
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
          'date'=>$this->date,
          'time'=>$this->time,
          'real_fee'=>$this->real_fee,
          'customer'=>$this->customer,
          'mobile'=>$this->mobile,
          'tearoom'=>$this->tearoom->name,
          'created_at'=>$this->created_at->toDateTimeString(),
          'statusToText'=>static::statusToText($this->status),
          'status'=>$this->status,
          'image'=>$this->tearoom->image.'?imageMogr2/thumbnail/!190x160r/auto-orient/strip/format/jpg/size-limit/$(fsize)!/interlace/1'
        ];

    }


}
