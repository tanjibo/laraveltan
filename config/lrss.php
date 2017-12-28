<?php
/**
 * |--------------------------------------------------------------------------
 * |
 * |--------------------------------------------------------------------------
 * Created by PhpStorm.
 * User: weaving
 * Date: 17/10/2017
 * Time: 4:20 PM
 */


//    return [
//        'experience' => [18610729170,13076966542],
//        'wechatSms'  => [
//
//            'app_id'    => 'wx744602f2d362bace',
//            'secret_id' => 'ca2563feb3d4e46eb94dd8bfda3fabe4',
//            'token'     => 'weaving',
//            'open_id'   => 'ohJajxKAue5qiLgaEkgB_4nUx7Xg',
//            # 微信模板发送通知 预约  成功 取消
//
//            'template_id_booking'     => "ETZ04TnlIqMxfyhwpWPGJdyu75m2Gcgd_VG53lunuEY",
//            'template_id_pay_success' => "3Q79kMhTzuOlfftk82cHjhhF8KkeImRznRwdiwt6vPo",
//            'template_id_cancel'     => "ngpwbUoQAt-lVQPuyEISGoGB0bwpMZDjRRrV3L9W_5M",
//        ],
//        'notification'=>[
//            'email'=>"1533954229@qq.com",
//            'from'=>"tanjibo@zhuozhenxuan.com",
//            'address'=>'北京濯振轩文化发展有限公司'
//        ]
//    ];

return [

    'experience' => explode(',',env('NOTIFY_MOBILE')),
    'tearoom' =>env('TEAROOM_NOTIFY_MOBILE'),
    'wechatSms'  => [
        'app_id'                  => env('WECHAT_NOTIFY_APPID'),
        'secret_id'               => env('WECHAT_NOTIFY_SECRET'),
        'token'                   => env('WECHAT_NOTIFY_TOKEN'),

        # 微信模板发送通知 预约  成功 取消
        'template_id_booking'     => "hJ5iCGcWjV_DTpUyFkkUyDhh1VKcHgKSyph6YIjWMKM",
        'template_id_pay_success' => "2B5z5ZCyY21lLxTaR4b0xJPA5lAI5NV-99YHJwEoKzk",
        'template_id_cancel'      => "xVLfxLr7anfaeYfw6b9_maDc_JSUZgIm2yiZOjB8Jyw",
    ],
    'notification'=>[
        'email'=>env('NOTIFY_TO_EMAIL'),
        'from'=>env('NOTIFY_FROM_EMAIL'),
        'address'=>'北京濯振轩文化发展有限公司'
    ]
];