<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use \Spatie\Permission\Models\Permission;
use  \Spatie\Permission\Models\Role;

class SeedRolesAndPermissionsData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        app()[ 'cache' ]->forget('spatie.permission.cache');


        // 先创建权限
        Permission::create([ 'name' => 'experience_room_show' ]);
        Permission::create([ 'name' => 'experience_room_create' ]);
        Permission::create([ 'name' => 'experience_room_update' ]);
        Permission::create([ 'name' => 'experience_room_del' ]);
        Permission::create([ 'name' => 'experience_room_date_lock' ]);

        //体验店 订单
        Permission::create([ 'name' => 'experience_booking_show' ]);
        Permission::create([ 'name' => 'experience_booking_create' ]);
        Permission::create([ 'name' => 'experience_booking_update' ]);
        Permission::create([ 'name' => 'experience_booking_del' ]);

        //评论
        Permission::create([ 'name' => 'experience_comment_show' ]);
        Permission::create([ 'name' => 'experience_comment_create' ]);

        //
        Permission::create([ 'name' => 'experience_settings_show' ]);

        //用户管理
        Permission::create([ 'name' => 'users_show' ]);
        Permission::create([ 'name' => 'users_create' ]);
        Permission::create([ 'name' => 'users_update' ]);
        Permission::create([ 'name' => 'users_del' ]);
        Permission::create([ 'name' => 'users_give_permissions' ]);

        //权限 [包括 role 和 permission]
        Permission::create([ 'name' => 'access_manger' ]);

        //图片展示
        Permission::create([ 'name' => 'art_show' ]);
        Permission::create([ 'name' => 'art_create' ]);
        Permission::create([ 'name' => 'art_update' ]);
        Permission::create([ 'name' => 'art_del' ]);

        //图片展示评论
        Permission::create([ 'name' => 'art_comment_show' ]);
        Permission::create([ 'name' => 'art_comment_create' ]);
        Permission::create([ 'name' => 'art_comment_del' ]);


        //----茶社
        Permission::create([ 'name' => 'tearoom_show' ]);
        Permission::create([ 'name' => 'tearoom_create' ]);
        Permission::create([ 'name' => 'tearoom_update' ]);
        Permission::create([ 'name' => 'tearoom_del' ]);
        Permission::create([ 'name' => 'tearoom_date_lock' ]);


        Permission::create([ 'name' => 'tearoom_booking_show' ]);
        Permission::create([ 'name' => 'tearoom_booking_create' ]);
        Permission::create([ 'name' => 'tearoom_booking_update' ]);
        Permission::create([ 'name' => 'tearoom_booking_del' ]);



        // 创建站长角色，并赋予权限
        $founder = Role::create([ 'name' => 'admin' ]);
        $founder = Role::create([ 'name' => 'superAdmin' ]);
        $founder = Role::create([ 'name' => 'art_manager' ]);
        $founder = Role::create([ 'name' => 'experience_manager' ]);
        $founder = Role::create([ 'name' => 'tearoom_manager' ]);



        //$maintainer->givePermissionTo('');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // 清除缓存
        app()[ 'cache' ]->forget('spatie.permission.cache');

        // 清空所有数据表数据
        $tableNames = config('permission.table_names');

        \Illuminate\Database\Eloquent\Model::unguard();
        DB::table($tableNames[ 'role_has_permissions' ])->delete();
        DB::table($tableNames[ 'model_has_roles' ])->delete();
        DB::table($tableNames[ 'model_has_permissions' ])->delete();
        DB::table($tableNames[ 'roles' ])->delete();
        DB::table($tableNames[ 'permissions' ])->delete();
        \Illuminate\Database\Eloquent\Model::reguard();
    }
}
