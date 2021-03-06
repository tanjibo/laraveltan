<?php
/**
 * |--------------------------------------------------------------------------
 * |菜单栏
 * |--------------------------------------------------------------------------
 * Created by PhpStorm.
 * User: weaving
 * Date: 29/11/2017
 * Time: 11:51 AM
 */
return [
    /**
     * |--------------------------------------------------------------------------
     * |安吉房间
     * |--------------------------------------------------------------------------
     */
    'experience_rooms/index'=>[
        'name' => '安吉列表',
        'url'  => 'experience_rooms.index',
    ],
    'experience_rooms/edit'=>[
        'name' => '修改房间',
        'url'  => 'experience_rooms.edit',
    ],
  'experience_rooms/lockDate'=>[
        'name' => '锁定房间',
        'url'  => 'experience_rooms.edit',
    ],
    /**
     * |--------------------------------------------------------------------------
     * |安吉订单
     * |--------------------------------------------------------------------------
     */
    'experience_bookings/index'=>[
        'name' => '订单列表',
        'url'  => 'experience_bookings.index',
    ],
    'experience_bookings/create'=>[
        'name' => '在线预约',
        'url'  => 'experience_bookings.create',
    ],
    'experience_bookings/edit'=>[
        'name' => '编辑',
        'url'  => 'experience_bookings.edit',
    ],
    'experience_bookings/show'=>[
        'name' => '订单详情',
        'url'  => 'experience_bookings.show',
    ],

    'experience_comments/index'=>[
        'name' => '评论列表',
        'url'  => 'experience_comments.index',
    ],
    'experience_comments/create'=>[
        'name' => '添加评论',
        'url'  => 'experience_comments.create',
    ],
    'experience_settings/index'=>[
        'name' => '通用设置',
        'url'  => 'experience_settings.index',
    ],
    'experience_send_sm/index'=>[
        'name' => '短信发送',
        'url'  => 'experience_send_sm.index',
    ],


    /**
     * |--------------------------------------------------------------------------
     * |添加栏目
     * |--------------------------------------------------------------------------
     */
    'experience_article/index'=>[
        'name' => '三舍栏目',
        'url'  => 'experience_article.index',
    ],
    'experience_article/create'=>[
        'name' => '添加三舍栏目文章',
        'url'  => 'experience_article.create',
    ],
    'experience_article/edit'=>[
        'name' => '编辑三舍栏目文章',
        'url'  => 'experience_article.edit',
    ],
    /**
     * |--------------------------------------------------------------------------
     * |发现栏目
     * |--------------------------------------------------------------------------
     */
    'experience_more_article/index'=>[
        'name' => '发现栏目',
        'url'  => 'experience_more_article.index',
    ],
    'experience_more_article/create'=>[
        'name' => '添加发现栏目文章',
        'url'  => 'experience_more_article.create',
    ],
    'experience_more_article/edit'=>[
        'name' => '编辑栏目文章',
        'url'  => 'experience_more_article.edit',
    ],

    /**
     * |--------------------------------------------------------------------------
     * |发现栏目
     * |--------------------------------------------------------------------------
     */
    'experience_wechat_fetcher/index'=>[
        'name' => '微信爬虫',
        'url'  => 'experience_wechat_fetcher.index',
    ],
    'experience_wechat_fetcher/create'=>[
        'name' => '添加爬虫',
        'url'  => 'experience_wechat_fetcher.create',
    ],
    'experience_wechat_fetcher/edit'=>[
        'name' => '修改微信文章类型',
        'url'  => 'experience_wechat_fetcher.edit',
    ],

    /**
     * |--------------------------------------------------------------------------
     * |合作用户
     * |--------------------------------------------------------------------------
     */
    'experience_partners/index'=>[
        'name' => "合作用户",
        'url'  => 'experience_partners.index',
    ],
    'experience_partners/create'=>[
        'name' => '添加合作',
        'url'  => 'experience_partners.create',
    ],




    /**
     * |--------------------------------------------------------------------------
     * |角色订单
     * |--------------------------------------------------------------------------
     */

    'roles/index'        => [
        'name' => '角色列表',
        'url'  => 'roles.index',
        'icon' => '',
    ],
    'roles/create' => [
        'name' => '添加角色',
        'url'  => 'roles.create',
        'icon' => '',
    ],
    'roles/edit'   => [
        'name' => '修改角色',
        'url'  => 'roles.edit',
        'icon' => '',
    ],

    /**
     * |--------------------------------------------------------------------------
     * |权限订单
     * |--------------------------------------------------------------------------
     */
    'permission/index'        => [
        'name' => '权限列表',
        'url'  => "permission.index",
        'icon' => '',
    ],
    'permission/create' => [
        'name' => "添加权限",
        'url'  => "permission.create",
        'icon' => "",
    ],
    'permission/edit'   => [
        'name' => "修改权限",
        'url'  => 'permission.edit',
        'icon' => "",
    ],
    /**
     * |--------------------------------------------------------------------------
     * |用户
     * |--------------------------------------------------------------------------
     */
    'user/index'   => [
        'name' => "用户列表",
        'url'  => 'user.index',
        'icon' => "",
    ],
  'user/edit'   => [
        'name' => "修改用户",
        'url'  => 'permission.edit',
        'icon' => "",
    ],
    'user/show'=>[
        'name' => '个人中心',
        'url'  => 'user.show',
    ],
    'user/userAllOrder'=>[
       'name'=>'个人订单列表',
       'url'=>"user.user_all_order"
    ],

    /**
     * |--------------------------------------------------------------------------
     * |艺术展示
     * |--------------------------------------------------------------------------
     */
    'art/index'   => [
        'name' => "展示列表",
        'url'  => 'art.index',
        'icon' => "",
    ],
    'art/create'   => [
        'name' => "添加展示",
        'url'  => 'art.create',
        'icon' => "",
    ],
    'art/edit'   => [
        'name' => "修改展示",
        'url'  => 'art.edit',
        'icon' => "",
    ],
    'art/show'   => [
        'name' => "展示详情",
        'url'  => 'art.show',
        'icon' => "",
    ],
    'art_comment/index'   => [
        'name' => "展示详情",
        'url'  => 'art_comment.index',
        'icon' => "",
    ],
    'art_comment/create'   => [
        'name' => "添加评论",
        'url'  => 'art_comment.create',
        'icon' => "",
    ],

    'art_suggestion/index'   => [
        'name' => "用户建议",
        'url'  => 'art_suggestion.index',
        'icon' => "",
    ],
    /**
     * |--------------------------------------------------------------------------
     * |茶舍
     * |--------------------------------------------------------------------------
     */
    'tearoom/index'   => [
        'name' => "茶舍列表",
        'url'  => 'tearoom.index',
        'icon' => "",
    ],
    'tearoom/create'   => [
        'name' => "添加茶舍",
        'url'  => 'tearoom.create',
        'icon' => "",
    ],
    'tearoom/edit'   => [
        'name' => "修改茶社",
        'url'  => 'tearoom.edit',
        'icon' => "",
    ],

    'tearoom_booking/index'   => [
        'name' => "茶舍订单列表",
        'url'  => 'tearoom_booking.index',
        'icon' => "",
    ],
    'tearoom_booking/create'   => [
        'name' => "在线预约",
        'url'  => 'tearoom_booking.create',
        'icon' => "",
    ],
    'tearoom_booking/show'   => [
        'name' => "订单详情",
        'url'  => 'tearoom_booking.show',
        'icon' => "",
    ],

];