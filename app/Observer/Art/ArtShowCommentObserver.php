<?php
/**
 * |--------------------------------------------------------------------------
 * |
 * |--------------------------------------------------------------------------
 * Created by PhpStorm.
 * User: weaving
 * Date: 19/12/2017
 * Time: 1:40 PM
 */

namespace App\Observer\Art;


use App\Events\ArtShowNotificationEvent;
use App\Models\ArtShowComment;
use App\Notifications\ArtShowCommentReply;

class ArtShowCommentObserver
{

    public function creating( ArtShowComment $comment )
    {
        //过滤
        $comment->comment = clean($comment->comment, 'user_topic_body') ?: '您输入的内容违规,请注意。如有下次，我们将采取一定的措施';
    }

    public function created( ArtShowComment $comment )
    {
        $comment->art_show->increment('comment_count');


        //有parent_id 说明是回复
        if ($comment->to_be_reply_id) {

            $parent = ArtShowComment::find($comment->to_be_reply_id);
            //顶级评论下的回复数
            ArtShowComment::find($comment->parent_id)->increment('reply_count', 1);
            //通知
            $parent->owner->notify(new ArtShowCommentReply($comment));
            //微信通知
            event(new ArtShowNotificationEvent(auth()->user(),$comment));
        }
    }

    public function deleting( ArtShowComment $comment )
    {
        //顶级评论
        if (!$comment->parent_id) {
            //查找顶级下的所有回复
            $model= ArtShowComment::where('parent_id', $comment->id);
            $count=$model->count()+1;
            $comment->art_show->decrement('comment_count', $count);
            $model->delete();
        }
        else {
            //不是顶级
           $model= ArtShowComment::where('id', $comment->parent_id);
           $model->decrement('reply_count');
        }


    }
}