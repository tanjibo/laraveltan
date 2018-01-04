<?php
/**
 * |--------------------------------------------------------------------------
 * |点赞
 * |--------------------------------------------------------------------------
 * Created by PhpStorm.
 * User: weaving
 * Date: 3/1/2018
 * Time: 11:58 AM
 */

namespace Repositories;


use App\Models\ArtShow;
use App\Models\ArtShowComment;
use App\Notifications\ArtshowCommentLike;

class ArtShowLikeAndCollectionRepository
{

    /**
     * @param ArtShow $artShow
     * @return string
     * 图片点赞
     */
    public function artShowLike(ArtShow $artShow){
        $return =[];
        if($artShow->likes()->ByWho(auth()->id())->WithType('like')->count()){
            $artShow->likes()->ByWho(auth()->id())->WithType('like')->delete();
            $artShow->decrement('like_count', 1);
           return $return['action_type'] = 'del';
        }else{
            $artShow->likes()->create(['user_id' => auth()->id(), 'is' => 'like']);
            $artShow->increment('like_count', 1);
          return  $return['action_type'] = 'add';
        }
    }

    /**
     * @param ArtShowComment $artShowComment
     * @return string
     * 评论点赞
     */
    public function artShowCommentLike(ArtShowComment $artShowComment){
        $return =[];
        if( $artShowComment->likes()->ByWho(auth()->id())->WithType('like')->count()){
            $artShowComment->likes()->ByWho(auth()->id())->WithType('like')->delete();
            $artShowComment->decrement('like_count', 1);
            return $return['action_type'] = 'sub';
        }else{
            $artShowComment->likes()->create(['user_id' => auth()->id(), 'is' => 'like']);
            $artShowComment->increment('like_count', 1);
            //点赞通知
            $artShowComment->owner->notify(new ArtshowCommentLike($artShowComment));

            return $return['action_type'] = 'add';
        }
    }


    public function artShowCollection(ArtShow $artShow){
        $return =[];
        if( $artShow->collections()->ByWho(auth()->id())->count()){
            $artShow->collections()->ByWho(auth()->id())->delete();
            $artShow->decrement('collection_count', 1);
            return $return['action_type'] = 'del';
        }else{
            $artShow->collections()->create(['user_id' => auth()->id()]);
            $artShow->increment('collection_count', 1);
            return $return['action_type'] = 'add';
        }
    }

}