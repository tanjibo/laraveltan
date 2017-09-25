<?php
/**
 * |--------------------------------------------------------------------------
 * |为了兼容以前的系统，放弃了laravel 自带的密码认证服务，以前同事采用yaf，然后 采用的密码加密迁移到
 * laravel,就是这个类的诞生，真的是非常😤
 * |--------------------------------------------------------------------------
 * Created by PhpStorm.
 * User: weaving
 * Date: 19/9/2017
 * Time: 2:03 PM
 */

namespace App\Foundation\Auth;


use Hautelook\Phpass\PasswordHash;

class LrssPassword
{
    /**
     * PHPass object
     * @var object
     */
    private static $_instance;

    /**
     * private construct
     */
    private function __construct()
    {

    }

    /**
     * Instance of PHPass
     * @return object
     */
    public static function getInstance()
    {
        is_null(self::$_instance) && self::$_instance = new PasswordHash(8,false);
        return self::$_instance;
    }

    /**
     * Hash Password
     * @param  string 	$secret   password
     * @return string
     */
    public static function hash(string $secret)
    {
        return self::getInstance()->HashPassword($secret);
    }

    /**
     * Validate Password
     * @param  string 	$secret   	password
     * @param  string 	$password 	password hash
     * @return boolean
     */
    public static function validate(string $secret, string $password)
    {
        return self::getInstance()->CheckPassword($secret, $password);
    }
}