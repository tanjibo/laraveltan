<?php

namespace App\Models;


use Reliese\Database\Eloquent\Model as Eloquent;

class ExperienceArticle extends Eloquent
{
    use \Illuminate\Database\Eloquent\SoftDeletes;
    protected $table = 'experience_article';
    protected $casts=[
      'type'=>'string'
    ];
    protected $fillable
        = [
            'parent_id',
            'cover_img',
            'desc',
            'view_count',
            'like_count',
            'comment_count',
            'content',
            'type',
            'author',
            'title',
            'sorts',
        ];
    const TYPE_TRANSFORM_PROCESS     = 0;    // 三舍
    const TYPE_CUSTOMER_STORY_FATHER = 1;    // 发现父文章
    const TYPE_CUSTOMER_STORY_CHILD  = 2;    // 发现子文章

    function articleChild()
    {
        return $this->hasMany(ExperienceArticle::class,"parent_id");
    }


    static function store(array $data )
    {
      return  static::query()->create($data);
    }
}
