<?php
/**
 * |--------------------------------------------------------------------------
 * |
 * |--------------------------------------------------------------------------
 * Created by PhpStorm.
 * User: weaving
 * Date: 11/10/2017
 * Time: 2:36 PM
 */

namespace Repositories;


use App\Http\Resources\Experience\ExperienceBookingCommentResource;
use App\Models\ExperienceBooking;
use App\Models\ExperienceBookingComment;
use Illuminate\Support\Facades\Auth;

class ExperienceRoomCommentRepository
{

    /**
     * @param $data
     * @param $booking_id
     * @param int $type
     * @return bool
     * 添加评论
     */
    public function addComment( $data, $booking_id, $type = 1 )
    {

        $class = static::_getBookingClassApi($type);

        //判断是否评论
        $isComment = $class::where([ [ 'id', $booking_id ], [ 'is_comment', 1 ] ])->value('is_comment');
        if ($isComment) return false;

        foreach ( $data as $item ) {
            $model = $this->_addSingleComment($item);
            if (!$model) return false;
        }

        // 一个订单只能评论一次
        $class::where('id', $booking_id)->update([ 'is_comment' => 1 ]);

        return true;


    }

    /**
     * @param array $data
     * @return $this|\Illuminate\Database\Eloquent\Model
     * 添加一条评论
     */
    private function _addSingleComment( array $data )
    {
        $model = ExperienceBookingComment::query()->create($data);

        if (isset($data[ 'pic' ])) {
            //超过10张图片处理
            if (count($data[ 'pic' ]) > 10) $data[ 'pic' ] = array_slice($data[ 'pic' ], 0, 10);
            $data[ 'pic' ] = array_map(
                function( $item ) {
                    return [ 'pic_url' => $item ];
                }, $data[ 'pic' ]
            );

            $model->pics()->createMany($data[ 'pic' ]);

        }
        return $model;
    }


    static function _getBookingClassApi( $type )
    {

        switch ( $type ) {
            case 1:
                return ExperienceBooking::class;

        }

    }

    /**
     * 前台评论列表
     */
    public function commentListApi( int $room_id, $has_pic = false )
    {
        $model = ExperienceBookingComment::query();

        $has_pic && $model->has('pics');

        return ExperienceBookingCommentResource::collection($model->where('commentable_id', $room_id)->orderBy('created_at', 'desc')->paginate())->additional([ 'code' => 200, 'message' => 'success' ]);

    }


    /**
     * @param int $booking_id
     * @param int $type
     * @return mixed
     * 评论的时候要显示每个房间的简介
     */
    public function getBookingRoomsApi( int $booking_id, int $type = 1 )
    {

        $class = static::_getBookingClassApi($type);

        $data  = $class::with('rooms')->find($booking_id);

       if(!$data) return false;

      return   $data->rooms->map(
            function( $item ) {
                $item                     =collect($item)->only([ 'id', 'name' ])->put('user_id', Auth::id()?:1);
                $item[ 'commentable_id' ] = $item[ 'id' ];
                unset($item[ 'id' ]);
                return $item;
            }
        );
    }

}