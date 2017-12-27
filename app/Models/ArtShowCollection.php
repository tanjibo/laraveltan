<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArtShowCollection extends Model
{
    protected $table = 'art_show_collection';

    protected $fillable = [ 'user_id', 'art_show_id' ];


    function art_show()
    {
        return $this->belongsTo(ArtShow::class,'art_show_id');
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
