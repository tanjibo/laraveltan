<?php
/**
 * |--------------------------------------------------------------------------
 * |
 * |--------------------------------------------------------------------------
 * Created by PhpStorm.
 * User: weaving
 * Date: 26/12/2017
 * Time: 2:03 PM
 */

namespace App\Http\Resources\Art;


use App\Http\Resources\Experience\UserResource;
use Illuminate\Http\Resources\Json\Resource;

class CommentResource extends Resource
{

    function toArray( $request )
    {
        return [
            'user_id'    => $this->user_id,
            'parent_id'  => $this->parent_id,
            'comment'    => $this->comment,
            'created_at' => $this->created_at->toDateString(),
            'like_count' => $this->count,
            'owner'      => $this->when($this->owner, new UserResource($this->owner)),
            'replies'    => $this->when($this->replies, new CommentResource($this->replies)),
        ];
    }
}