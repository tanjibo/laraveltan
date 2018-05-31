<?php
/**
 * |--------------------------------------------------------------------------
 * |
 * |--------------------------------------------------------------------------
 * Created by PhpStorm.
 * User: weaving
 * Date: 28/5/2018
 * Time: 3:48 PM
 */
return [
    [
        'name'       => '了如三舍',
        'sub_button' => [
            [
                'type' => 'view',
                'name' => '微店分享返现',
                'url'  => 'https://weidian.com/s/1140693382?wfr=c&ifr=shopdetail',
            ],
            [
                'type' => 'view',
                'name' => "微信商城",
                'url'  => "https://m.liaorusanshe.com/",
            ],
        ],

    ],
    [
        'name'       => '体验中心',
        'sub_button' => [
            [
                //'type'     => 'miniprogram',
                'type'     => 'view',
                'name'     => '立即预订',
               // "appid"    => config("minilrss.default.appid"),
              //  "pagepath" => "pages/roomList",
                'url'      => 'http://m.liaorusanshe.com/experience',
            ],
//            [
//                'type'     => "media_id",
//                'name'     => '联系客服',
//                'media_id' => "sS3oZ-BZN0Uol7kCV2K2bvTfDI7GYCsSQEo1YvJ03GA",
//            ],

        ],
    ],
    [
        'name'       => '茶舍预订',
        'sub_button' => [
            [
                'type' => "view",
                'name' => '茶舍介绍',
                'url'  => "https://m.liaorusanshe.com/h5/tearoom",
            ],
            [
                'type' => "view",
                'name' => '立即预订',
                'url'  => "https://m.liaorusanshe.com/tearoom",
            ],
            [
                'type' => "view",
                'name' => '我的预订',
                'url'  => "https://m.liaorusanshe.com/tearoom#/order/list",
            ],
        ],
    ],
];
