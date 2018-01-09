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
            'to_be_reply_id'
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
        return $this->belongsTo(User::class, 'user_id')->select('nickname','id','avatar','mobile','art_open_id');
    }

    public function replies_to_user()
    {
        return $this->belongsTo(ArtShowComment::class, 'to_be_reply_id')->with('owner')->withTrashed();
    }

    public function replies()
    {
        return $this->belongsTo(ArtShowComment::class, 'parent_id')->with('owner');
    }

    public function childs(){
        return $this->hasMany(ArtShowComment::class,'parent_id')->with(['replies_to_user','owner','likes'=>function($query){
            $query->where('user_id',auth()->id());
        }])->orderBy('like_count','desc');
    }

    /**
     * 限制查询两
     */
    public function childs2limit(){
        return $this->hasMany(ArtShowComment::class,'parent_id')->with(['replies_to_user','owner','likes'=>function($query){
            $query->where('user_id',auth()->id());
        }])->orderBy('like_count','desc')->limit(2);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     * 点赞
     */
    public function  likes(){
          return $this->morphMany(ArtShowLike::class, 'likable');
}



    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($model) {
            //顶级评论
//            if(!$model->parent_id){
//                ArtShowComment::where('parent_id',$model->id)->delete();
//            }else{
//                ArtShowComment::where('_to_be_reply_id',$model->id)->delete();
//            }

           // $model->owner->notifications()->delete();
//            $model->notifications()->delete();
        });
    }

}
