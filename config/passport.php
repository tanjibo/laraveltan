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
    'grant_type' => env('OAUTH_GRANT_TYPE'),
    'client_id' => env('OAUTH_CLIENT_ID'),
    'client_secret' => env('OAUTH_CLIENT_SECRET'),
    'scope' => env('OAUTH_SCOPE', '*'),
];