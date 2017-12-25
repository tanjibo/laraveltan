<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArtShowCommentLike extends Model
{
    protected $table = 'art_show_comment_like';

    protected $fillable = [ 'user_id', 'art_show_comment_id' ];


    function comment()
    {
        return $this->belongsTo(ArtShowComment::class,'art_show_comment_id');
    }


    /**
     * @param $data
     * @return bool|mixed|null
     * 点赞
     */
  public static  function toggle( $data )
    {

        $model = static::query()->where($data)->first();

        return $model? $model->delete() : static::query()->create($data);

    }
}
