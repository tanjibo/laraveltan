<?php
/**
 * |--------------------------------------------------------------------------
 * |
 * |--------------------------------------------------------------------------
 * Created by PhpStorm.
 * User: weaving
 * Date: 25/9/2017
 * Time: 3:42 PM
 */
return [
    'experience'=>[
        'grant_type' => env('EXPERIENCE_OAUTH_GRANT_TYPE'),
        'client_id' => env('EXPERIENCE_OAUTH_CLIENT_ID'),
        'client_secret' => env('EXPERIENCE_OAUTH_CLIENT_SECRET'),
        'scope' => env('EXPERIENCE_OAUTH_SCOPE', '*'),
    ],
    'art'=>[
        'grant_type' => env('ART_OAUTH_GRANT_TYPE'),
        'client_id' => env('ART_OAUTH_CLIENT_ID'),
        'client_secret' => env('ART_OAUTH_CLIENT_SECRET'),
        'scope' => env('ART_OAUTH_SCOPE', '*'),
    ],
    'tearoom'=>[
        'grant_type' => env('TEAROOM_OAUTH_GRANT_TYPE'),
        'client_id' => env('TEAROOM_OAUTH_CLIENT_ID'),
        'client_secret' => env('TEAROOM_OAUTH_CLIENT_SECRET'),
        'scope' => env('TEAROOM_OAUTH_SCOPE', '*'),
    ],

];