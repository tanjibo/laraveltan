<?php
/**
 * |--------------------------------------------------------------------------
 * |
 * |--------------------------------------------------------------------------
 * Created by PhpStorm.
 * User: weaving
 * Date: 19/12/2017
 * Time: 2:36 PM
 */

namespace App\Observer\Art;


use App\Models\ArtShowCommentLike;

class ArtShowCommentLikeObserver
{

    public function created( ArtShowCommentLike $like )
    {

        $like->comment->increment('like_count');

    }

    public function deleted( ArtShowCommentLike $like )
    {
        $like->comment->decrement('like_count',1);
    }
}