<?php

namespace App\Http\Resources\Art;

use Illuminate\Http\Resources\Json\Resource;

class ArtShowResource extends Resource
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
           'big_img'=>$this->cover,
           'small_img'=>$this->cover,
           'created_at'=>$this->created_at->toDateString(),
           'name'=>$this->name,
           'like_count'=>$this->like_count,
           'desc'=>$this->desc,
           'comment_count'=>$this->comment_count
       ];
    }
}
