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
           'small_img'=>$this->cover.'?imageView2/2/h/300/format/jpg/q/60|imageslim',
           'created_at'=>$this->created_at->toDateString(),
           'name'=>$this->name,
           'like_count'=>$this->like_count,
           'desc'=>$this->desc,
           'view_count'=>$this->view_count,
           'share_count'=>$this->share_count,
           'comment_count'=>$this->comment_count,
           'collection_count'=>$this->collection_count,
          // 'is_auth_user_liked'=>count($this->likes),
           'is_auth_user_collected'=>$this->collections()->ByWho(auth()->id())->count(),
       ];
    }
}
