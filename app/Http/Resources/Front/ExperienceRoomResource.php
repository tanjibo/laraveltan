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
     */
    protected function sliderUrl()
    {
        return [ 'slider_url' => ExperienceRoomSliderResource::collection($this->experience_room_sliders) ];
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
