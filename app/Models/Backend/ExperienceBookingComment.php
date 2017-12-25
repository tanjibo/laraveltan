<?php

/**
 * Created by Reliese Model.
 * Date: Wed, 06 Dec 2017 15:59:04 +0800.
 */

namespace App\Models\Backend;


use Spatie\Activitylog\Traits\LogsActivity;

/**
 * Class ExperienceBookingComment
 *
 * @property int $id
 * @property int $user_id
 * @property string $content
 * @property int $commentable_id
 * @property string $commentable_type
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * @property int $score
 * @property int $parent_id
 * @property int $level
 * @property int $status
 * @property int $is_reply
 *
 * @package App\Models
 */
class ExperienceBookingComment extends \App\Models\ExperienceBookingComment
{
    use \Illuminate\Database\Eloquent\SoftDeletes;

   use LogsActivity;


    protected $hidden
        = [
            'deleted_at',
        ];



    public function commentable()
    {
        return $this->morphTo();
    }

    /**
     * @param array $commentData
     * @return bool
     * 添加评论
     */
    public static function addComment( array $commentData )
    {

        if ($model = static::query()->create($commentData)) {
            //如果有上传图片的话
            if (count($commentData[ 'imgUrl' ])) {
                $imgUrl = collect($commentData[ 'imgUrl' ])->transform(
                    function( $item ) {
                        $arr[ 'pic_url' ] = $item[ 'url' ];
                        return $arr;
                    }
                );
                $model->experience_comment_pics()->createMany($imgUrl->toArray());
            }
            return $model;
        }
        else {
            return false;
        }

    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     * 评论图片列表
     */
    public function experience_comment_pics()
    {
        return $this->hasMany(ExperienceBookingCommentPic::class, 'comment_id');
    }


    public function experience_rooms(){
        return $this->belongsTo(ExperienceRoom::class,'commentable_id');
    }

}
