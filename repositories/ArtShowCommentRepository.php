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


use App\Http\Resources\Art\CommentResource;
use App\Models\ArtShow;
use App\Models\ArtShowComment;

class ArtShowCommentRepository
{

    /**
     *评论列表
     */
    public function commentList(ArtShow $artShow){


        //评论信息
      return  $artShow->comments()->where('parent_id',0)->with(['owner','likes'=>function($query){
            $query->where('user_id',auth()->id());

        }])->paginate(10);


    }


    /**
     * @param ArtShowComment $artShowComment
     * 评论详情
     */
    public function commentDetail(ArtShowComment $artShowComment){

        $data=$artShowComment->childs()->paginate(2);

        $links=['current_page'=>$data->currentPage(),'total'=>$data->lastPage()];

        $comment = CommentResource::collection($data);
        // 标记为已读，未读数量清零
        return['data'=>$comment,'link'=>$links];
    }
}