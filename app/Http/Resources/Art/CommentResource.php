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


use App\Http\Resources\UserResource;
use Illuminate\Http\Resources\Json\Resource;

class CommentResource extends Resource
{


    function toArray( $request )
    {
      return  [
             'id'=>$this->id,
            'user_id'    => $this->user_id,
            //是否可以删除
            'is_can_del'=>$this->user_id==auth()->id()?1:0,
             //是否可以回复，去掉自己回复自己的情况
             'is_can_reply'=>$this->user_id!=auth()->id()?1:0,
            'parent_id'  => $this->parent_id,
            'comment'    => $this->comment,
            'created_at' => $this->created_at->diffForHumans(),
            'reply_count'=>$this->reply_count,
            'like_count' => $this->count?:0,
            'is_auth_user_liked'=>count($this->likes)?1:0,
            'to_be_reply_id'=>$this->to_be_reply_id,
             'art_show_id'=>$this->art_show_id,
            //评论人
            'nickname'=>$this->owner?$this->owner->nickname:'错误',
            'avatar'=>$this->owner?$this->owner->avatar:'avatar',

            //是否有子楼层

            'childs2limit'=>$this->mergeWhen(count($this->childs2limit),static::collection($this->childs2limit)),
            //是否@某人
            'reply_to_user'=>$this->when($this->parent_id!=$this->to_be_reply_id,$this->filterReplyToUser($this->replies_to_user)?:[])
        ];



    }

    private function  filterReplyToUser($reply){
       if(!$reply) return [];
        return [
            'user_id'    => $reply->user_id,
            'comment'    => $reply->comment,
            'nickname'=>$reply->owner->nickname,
            'avatar'=>$reply->owner->avatar
        ];
    }
}
