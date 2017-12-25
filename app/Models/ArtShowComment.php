<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ArtShowComment extends Model
{
    use SoftDeletes;
    protected $table = 'art_show_comment';

    protected $fillable
        = [
            'comment',
            'user_id',
            'art_show_id',
            'parent_id',
            'like_count',
        ];

    public function art_show()
    {
        return $this->belongsTo(ArtShow::class, 'art_show_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * 评论的拥有者
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function replies()
    {
        return $this->belongsTo(ArtShowComment::class, 'parent_id')->withTrashed()->with('owner');
    }

    public function  likes(){
       return $this->hasManyThrough(ArtShowCommentLike::class,User::class,'id');
}

    public function isLikeBy( $user)
    {
        return ArtShowCommentLike::where('art_show_comment_id',$this->id)->where('user_id',auth()->id())->first();
    }



}
