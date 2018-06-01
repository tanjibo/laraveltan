<?php
/**
 * |--------------------------------------------------------------------------
 * |
 * |--------------------------------------------------------------------------
 * Created by PhpStorm.
 * User: weaving
 * Date: 29/5/2018
 * Time: 1:32 PM
 */

namespace App\Foundation\Lib;


use Intervention\Image\Facades\Image;

class PostImgCombine
{

    static function makePoster( $user, $activity )
    {


        $data = static::qrCodeParams($user, $activity);

        $result = app("wechat.official_account")->qrcode->temporary($data, 6 * 24 * 3600);
        $url    = app("wechat.official_account")->qrcode->url($result[ "ticket" ]);

        $img = Image::make(public_path() .$activity->poster_base_img_url);
        //$img = Image::make(public_path() .'/officialAccount/wechat.jpg');

        $img->text(
            $user->nickname, 180, 1060, function( $font ) {
            $font->file(public_path() . '/officialAccount/simhei.ttf');
            $font->size(28);
            $font->color('#fff');
            $font->align("center");
        }
        );
        $qrcode = Image::make($url)->resize(182, 182);
        $avatar = Image::make($user->avatar)->resize(182, 182);

        $img->insert($qrcode, 'bottom-right', 95, 200);
        $img->insert($avatar, 'bottom-left', 95, 200);

        $base_path = public_path() . '/officialAccount/poster/';

        is_dir($base_path) or mkdir($base_path, 0770);
        $fileName = str_random(16) . '.jpg';
        $filePath = $base_path . $fileName;
        $img->save($filePath,70);

        $data = app("wechat.official_account")->material->uploadImage($filePath);


        return [ 'poster_media_id' => $data[ 'media_id' ], 'poster_url' => asset('/officialAccount/poster/'.$fileName) ];
    }


    /**
     * @param $user
     * @param $activity
     * @return string
     * 获得生成二维码的参数
     */
    static function qrCodeParams( $user, $activity )
    {

        return json_encode(
            [
                "expire_seconds" => 20,
                "action_name"    => "QR_STR_SCENE",
                "action_info"    => [
                    "scene" => [
                        "scene_str"            => $user->open_id,
                        'official_activity_id' => $activity->id,
                    ],
                ],
            ]
        );
    }

}