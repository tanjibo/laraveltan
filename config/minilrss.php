<?php

return [

        'default' => [

            'appid'            => 'wx28cb8b1d2b4514d0',

            'secret'           => 'ddf1aa240a07266752638e5510aba064',
            'grant_type' => 'authorization_code',

        ],
        'art' => [
            /**
             * 小程序APPID
             */
            'appid'            => 'wxbeb15461a28f85c1',
            /**
             * 小程序Secret
             */
            'secret'           => 'c0f6e584a17eefef7a698e0bbbf1fce0',
            'grant_type' => 'authorization_code',

        ],
        /**
         * 小程序登录凭证 code 获取 session_key 和 openid 地址，不需要改动
         */
        'code2session_url' => "https://api.weixin.qq.com/sns/jscode2session?appid=%s&secret=%s&js_code=%s&grant_type=authorization_code",


];
