<?php
/**
 * |--------------------------------------------------------------------------
 * |
 * |--------------------------------------------------------------------------
 * Created by PhpStorm.
 * User: weaving
 * Date: 11/10/2017
 * Time: 1:36 PM
 */

namespace Repositories;



use Qiniu\Auth;
use Qiniu\Storage\UploadManager;

class QiniuUploadRepository
{
// 需要填写你的 Access Key 和 Secret Key
    private static $accessKey = 'KlJYnXDYLIwwABs6zoPmRGVc6xbAP4Kuzifr-fxM';

    private static $secretKey = 'UR4jTeUYIyClk51yUQ3XGYPoG8XMsimTY5uLvvVA';

    // 要上传的空间
    private static $bucket = 'experience-room';

    private static $picPrefix = '/experience/userupload/';

    public static $url='http://static.liaorusanshe.com/';

    /**
     * @param string $data 二进制数据
     * @param string $filename 上传图片的文件名称
     * @return array 上传图片到七牛
     */
    static function upload( $data = '', $filename = '' )
    {
        $auth  = new Auth(static::$accessKey, static::$secretKey);
        $token = $auth->uploadToken(static::$bucket);

        if ($filename)
            $filename = static::$picPrefix . $filename;
        else
            $filename = static::$picPrefix . date("Ym") . "/" . substr(md5(time() . mt_rand(10, 99)),0,10). ".png";


        // 初始化 UploadManager 对象并进行文件的上传。
        $uploadMgr = new UploadManager();

        list(
            $ret, $err
            )
            = $uploadMgr->put($token, $filename, $data);


        if ($err !== null) {
            return '';
        }
        else {
            return static::$url.$ret[ 'key' ];
        }
    }

    /**
     * @param string $data
     * @param string $filename
     * @return string base64 上传
     */
    static function uploadToBase64($data='',$filename=''){
        $auth  = new Auth(static::$accessKey, static::$secretKey);
        $token = $auth->uploadToken(static::$bucket);
        $uploadMgr=new UploadManager();

        if ($filename)
            $filename = static::$picPrefix . $filename;
        else
            $filename = static::$picPrefix . date("Ym") . "/" . substr(md5(time() . mt_rand(10, 99)),0,10). ".png";


        list(
            $ret, $err
            )
            = $uploadMgr->putFile($token, $filename, $data);

        if ($err !== null) {
            return '';
        }
        else {
            return static::$url.$ret[ 'key' ];
        }
    }



}