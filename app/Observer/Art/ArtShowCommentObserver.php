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
        }
    }

    public function deleting( ArtShowComment $comment )
    {
        //顶级评论
        if (!$comment->parent_id) {
            $model= ArtShowComment::where('parent_id', $comment->id);
        }
        else {
           $model= ArtShowComment::where('to_be_reply_id', $comment->id);

        }

        $comment->art_show->decrement('comment_count', $model->count());
        $model->delete();

    }
}