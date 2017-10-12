<?php
/**
 * |--------------------------------------------------------------------------
 * |
 * |--------------------------------------------------------------------------
 * Created by PhpStorm.
 * User: weaving
 * Date: 11/10/2017
 * Time: 1:31 PM
 */

namespace App\Http\ApiControllers\Front;


use App\Http\ApiControllers\ApiController;
use Illuminate\Http\Request;
use Repositories\ExperienceRoomCommentRepository;
use Repositories\QiniuUploadRepository;

class ExperienceBookingCommentController extends ApiController
{
    protected $comment;

    function __construct( ExperienceRoomCommentRepository $repository )
    {
        $this->comment = $repository;
    }


    function commentList( Request $request )
    {
        return $this->comment->commentListApi($request->room_id, $request->has_pic ?: false);

    }

    /**
     * @param Request $request
     * /**
     * 添加评论
     * [['content'=>'33423','commentable_id=>7,'pic'=>['http://baidu.com'],'score'=>5,'user_id'=>9]]
     */
    function addComment( Request $request )
    {
        $originData=$request->all();
        $commentData = array_filter(
            collect($originData)->map(
                function( $item ) {
                    return (function() use ( $item ) {
                        if (!($item[ 'commentable_id' ] && $item[ 'content' ] && $item[ 'score' ] && $item[ 'user_id' ])) {
                            return false;
                        }
                        return $item;
                    })();
                }
            )->toArray()
        );
        if (!$commentData) return $this->error('评论数据不能为空');

        //添加评论
        $model=$this->comment->addComment($commentData, $request->booking_id, $request->type ?: 1);
          if($model)
          return $this->success([]);
          return $this->error('添加错误');
    }

    /**
     * @param Request $request
     * @return mixed
     * 获取评论房间信息
     */
    public function getBookingRooms(Request $request)
    {
       $data= $this->comment->getBookingRoomsApi($request->booking_id,$request->type?:1);

       return $data?$this->success($data):$this->error('error');
    }

    /**
     * 上传图片到七牛
     */
    public function upload()
    {
        $stream = file_get_contents($_FILES[ 'image' ][ 'tmp_name' ]);
        //上传图片
        return $this->success(['url'=>QiniuUploadRepository::upload($stream)]);
    }
}