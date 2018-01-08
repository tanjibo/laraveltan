<?php
/**
 * |--------------------------------------------------------------------------
 * |
 * |--------------------------------------------------------------------------
 * Created by PhpStorm.
 * User: weaving
 * Date: 26/12/2017
 * Time: 6:02 PM
 */

namespace App\Http\Resources\Art;


use App\Models\ArtShowComment;
use Illuminate\Http\Resources\Json\Resource;

class NotificationResource extends Resource
{



    public function toArray( $request )
    {
        return [
            'type'       => class_basename($this->type),
            'created_at' => $this->created_at->toDateTimeString(),
            'data'       => static::filterData($this->data),
            'read_at'=>$this->read_at?true:false
        ];
    }

    static function filterData( $data )
    {
        var_dump($data);exit;
        if(!count($data)) return [];
        $data[ 'user_avatar' ] = static::https($data[ 'user_avatar' ]);

        $data['reply']=htmlspecialchars_decode($data['reply']);
        $data['is_del_comment']=ArtShowComment::query()->where('id',$data['comment_id'])->count();
        $data['is_del_father_comment']=ArtShowComment::query()->where('id',$data['parent_comment_id'])->count();
        return $data;
    }


    static function https( $avatar )
    {
        preg_match('/^(http[s]?)\:\/\/(.+)/i', $avatar, $data);

        if ($data[ 1 ] == 'http')
            return str_replace('http', 'https', $avatar);
        else
            return $avatar;
    }
}