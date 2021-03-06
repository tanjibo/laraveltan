<?php

namespace App\Http\Resources\Experience;

use App\Models\ExperienceArticle;
use Illuminate\Http\Resources\Json\Resource;

class ArticleResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray( $request )
    {
        return [
            'id'=>$this->id,
//            'cover_img' => $this->type==ExperienceArticle::TYPE_CUSTOMER_STORY_CHILD?$this->cover_img."?imageMogr2/gravity/Center/crop/61x61":$this->cover_img."?imageMogr2/gravity/Center/crop/389x216",
            'cover_img' => $this->cover_img,
            'title'     => $this->title,
            'desc'      => $this->desc,
            'content'   =>  $this->when($request->detail,$this->content),
            'article_child'=>$this->when(isset($request->type), ArticleResource::collection($this->articleChild))
        ];
    }

}
