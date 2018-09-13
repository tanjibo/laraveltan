<?php

namespace App\Http\Resources\Experience;

use App\Models\ExperienceRoomCommonSetting;
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
                'cover'          => $this->https($this->cover),
                'type'           => $this->type,
                'is_prepay'      => $this->is_prepay,
                'comment_counts' => $this->comments()->count(),//评价数
                'comment_score'  => ceil($this->comments()->avg('score')),
                'design_concept' => $this->design_concept,
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
                                     'intro' => $this->intro,
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

        $a = $this->attach_url ? ExperienceRoomCommonSetting::attachUrl($this->attach_url) : [];
        if ($a) {
            $a = collect($a)->map(
                function( $item ) {
                    return $this->https($item->url);
                }
            );
        }
        return [
            'attach_url' => $a,
        ];

    }

    /**
     * @return array
     * 轮播图
     * 有待处理
     */
    protected function sliderUrl()
    {
        $url = $this->experience_room_sliders()->pluck('url')->map(
            function( $item ) {
//           if($item) return $item.'?imageView2/q/70/interlace/1|imageslim';
                if ($item) return $this->https($item);
                return;
            }
        )
        ;
        return [ 'slider_url' => $url ];
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

    /**
     * @param $url
     * @return mixed
     * 转换http https 为https
     */
    private function https( $url )
    {
        $url = $this->qiniuUrlParams($url);
        preg_match('/^(http[s]?)\:\/\/(.+)/i', $url, $data);
        if ($data[ 1 ] && $data[ 1 ] == 'http')
            return str_replace('http', 'https', $url);
        else
            return $url;
    }

    private function qiniuUrlParams( $url )
    {
        return $url . '?imageMogr2/auto-orient/strip/format/jpg/size-limit/$(fsize)!/interlace/1';
    }
}
