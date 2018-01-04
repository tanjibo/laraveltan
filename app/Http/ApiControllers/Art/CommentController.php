<?php

namespace App\Http\ApiControllers\Art;

use App\Http\ApiControllers\ApiController;
use App\Http\Requests\ArtCommentRequest;
use App\Http\Resources\Art\ArtShowResource;
use App\Http\Resources\Art\CommentResource;
use App\Models\ArtShow;
use App\Models\ArtShowComment;
use Illuminate\Http\Request;
use Repositories\ArtShowCommentRepository;

class CommentController extends ApiController
{

    protected $repository;

    public function __construct( ArtShowCommentRepository $repository )
    {
        $this->repository = $repository;
    }


    public function commentList( ArtShow $art_show)
    {

       $p= $this->repository->commentList($art_show);

        $links=['current_page'=>$p->currentPage(),'total'=>$p->lastPage()];

        $data= CommentResource::collection($p);
        // 标记为已读，未读数量清零
        return  $this->success(['data'=>$data,'link'=>$links]);
    }


    /**
     * @param ArtShowComment $art_comment
     * @return mixed
     */
    public function commentDetail(ArtShowComment $art_comment){

        $data=$this->repository->commentDetail($art_comment);

        $links=['current_page'=>$data->currentPage(),'total'=>$data->lastPage()];

        $comment = CommentResource::collection($data);
        // 标记为已读，未读数量清零
        $c=['data'=>$comment,'link'=>$links,'comment'=>new CommentResource($art_comment)];

         return   $this->success($c);
    }


    /**
     * art_show_id
     * comment
     * parent_id 如果有代表这是一个回复
     * @param ArtCommentRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * 添加评论
     */
    public function store( ArtCommentRequest $request )
    {
        //获取登录用户的id
        $request[ 'user_id' ] = auth()->id();
        $model   = ArtShowComment::query()->create($request->all());

        return $model ? $this->success(new CommentResource($model)) : $this->error('添加评论错误');

    }


    /**
     * @param Request $request
     * @param $id 评论id
     * @return mixed
     * id
     */
    public function destroy( Request $request, $id )
    {
        $art_comment = ArtShowComment::query()->findOrFail($id);

        return $this->success($art_comment->delete());
    }
}
