<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(
            'user', function( Blueprint $table ) {
            $table->string('level_title')->default('小白');
            $table->string('signature')->default('');
            $table->string('password')->default('');
            $table->char('is_receive_email')->default('T');
            $table->ipAddress('last_login_ip')->default("");
            $table->boolean("is_lrss_staff")->default(0);//是不是lrss员工
            $table->tinyInteger('age')->default(18)->comment('年龄');
            $table->date('birthday')->default(\Carbon\Carbon::now())->comment('生日');
            $table->string('profession',20)->default('');
            $table->string('nationality',40)->default('');
            $table->string('education',40)->default('');
            $table->string('id_card',40)->default('');
            $table->string('qq',40)->default('');
            $table->string('weibo',40)->default('');
            $table->string('wechat',45)->default('');
            $table->string('remark')->default('');
            $table->string('intention',50)->default('');
            $table->string('terminal',100)->default('web');
            $table->integer('notification_count')->default(0)->comment('通知总数');
            $table->smallInteger('is_superadmin')->default(0)->comment('通知总数');
           $table->rememberToken();

        }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table(
            'user', function( Blueprint $table ) {
            $table->dropColumn([ 'level_title', 'signature', 'password','is_receive_email','is_lrss_staff','last_login_ip','age','qq','weibo','wechat','remark','intention','id_card','education','nationality','profession','birthday','terminal','notification_count','remember_token','is_superadmin']);
        }
        );
    }
}
