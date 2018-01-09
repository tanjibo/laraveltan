<?php

namespace App\Http\ApiControllers\Art;

use App\Foundation\Lib\ArtShowWechatNotify;
use App\Http\ApiControllers\ApiController;
use App\Http\Requests\ArtCommentRequest;
use App\Http\Resources\Art\ArtShowResource;
use App\Http\Resources\Art\CommentResource;
use App\Models\ArtShow;
use App\Models\ArtShowComment;
use App\Models\User;
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
        $user=ArtShowComment::query()->where('id',$request->to_be_reply_id)->value('user_id');

        $data=[
//           'art_open_id'=>(string)$model->replies_to_user->owner->art_open_id,
            'open_id'=>User::where('id',$user)->value('art_open_id'),
//            'open_id'=>auth()->user()->art_open_id,
            'form_id'=>request()->form_id,
            'reply_user'=>auth()->user()->nickname,
            'parent_comment_id'=>$model->parent_id,
            'reply_comment'=>$model->comment,
            'art_show_name'=>$model->art_show->name,
            'date'=>$model->created_at->toDateTimeString()
        ];
        dd($data);exit;
        (new ArtShowWechatNotify)->commentReply($data);

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
