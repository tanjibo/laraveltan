<?php

namespace App\Http\Resources\Front;

use App\Models\ExperienceRoomCommonSetting;
use App\Models\ExperienceRoomSlider;
use Illuminate\Http\Resources\Json\Resource;

class ExperienceRoomResource extends Resource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray( $request )
    {

        return $this->price() + [
                'id'             => $this->id,
                'name'           => $this->name,
                'price'          => $this->price,
                'cover'          => $this->cover,
                'type'           => $this->type,
                'comment_counts' => $this->comments()->count(),//评价数
                'comment_score'  => ceil($this->comments()->avg('score')),
                'attach'         => array_filter(
                    explode(' ', $this->attach), function( $item ) {
                    if ($item) return true;
                }
                ),
                $this->roomDetail($request),


            ];
    }


    public function roomDetail( $request )
    {
        //详情的时候
        return $this->mergeWhen(
            $request->room_id, array_merge_recursive(
                            [
                                'design_concept' => $this->design_concept,
                                'intro'          => $this->intro,
                            ], $this->attachUrl(), $this->sliderUrl(), $this->comment(), $this->un()
                        )
        );
    }

    /**
     * @return array
     * 配套设施
     */
    protected function attachUrl()
    {
        return [
            'attach_url' => $this->attach_url ? ExperienceRoomCommonSetting::attachUrl($this->attach_url) : [],
        ];
    }

    /**
     * @return array
     * 轮播图
     * 有待处理
     */
    protected function sliderUrl()
    {
       $url=$this->experience_room_sliders()->pluck('url')->map(function($item){
//           if($item) return $item.'?imageView2/q/70/interlace/1|imageslim';
           if($item) return $item;
           return;
       });
        return [ 'slider_url' =>$url ];
    }

    /**
     * @return array
     * 评论简介
     */
    protected function comment()
    {
        return [ 'comment' => new ExperienceBookingCommentResource($this->comments()->orderBy('created_at', 'desc')->first()) ];
    }

    /**
     * @return array
     * 退订
     */
    protected function un()
    {
        return [ 'unsubscribe' => ExperienceRoomCommonSetting::unsubscribe() ];
    }
}
