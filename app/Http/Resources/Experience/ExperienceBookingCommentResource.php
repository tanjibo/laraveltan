<?php

namespace App\Http\Resources\Experience;


use App\Http\Resources\UserResource;
use Illuminate\Http\Resources\Json\Resource;

class ExperienceBookingCommentResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray( $request )
    {
        return (new UserResource($this->user))->jsonSerialize() +
               [
                   'id'         => $this->id,
                   'content'    => $this->content,
                   'score'      => $this->score,
                   'reply'      => $this->reply,
                   'created_at' => $this->created_at->toDateString(),
                   'pics'=>$this->when(isset($request->room_id),$this->pics()->pluck('pic_url'))

               ];
    }



}
