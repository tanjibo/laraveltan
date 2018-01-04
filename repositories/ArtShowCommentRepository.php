<?php
/**
 * |--------------------------------------------------------------------------
 * |
 * |--------------------------------------------------------------------------
 * Created by PhpStorm.
 * User: weaving
 * Date: 3/1/2018
 * Time: 2:05 PM
 */

namespace Repositories;


use App\Models\ArtShow;
use App\Models\ArtShowComment;

class ArtShowCommentRepository
{

    /**
     *评论列表
     */
    public function commentList(ArtShow $artShow){

       $comment= $artShow->comments()->with(['limit2Childs','owner'])->where('parent_id',0)
        ->orderBy('like_count','desc')->orderBy('created_at','desc')->paginate(10);

       return $comment;

    }


    /**
     * @param ArtShowComment $artShowComment
     * 评论详情
     */
    public function commentDetail(ArtShowComment $artShowComment){

        $data=$artShowComment->childs()->paginate(1)->toArray();

        $links=['current_page'=>$data->currentPage(),'total'=>$data->lastPage()];

        $comment = ArtComment::collection($data);
        // 标记为已读，未读数量清零
        $user->markAsRead();

        return ['data'=>$comment,'link'=>$links];
    }
}