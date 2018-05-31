<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOfficialActivity extends Migration
{



    public function up()
    {
        Schema::create(
            'official_account_default_setting', function( Blueprint $table ) {
            $table->increments('id');
            $table->text('default_welcome')->comment('默认欢迎语');
            $table->text('be_recommend_welcome')->comment('被推荐获得抽奖码欢迎语');
            $table->text('auto_reply_welcome')->default("已收到您的留言，请稍等")->comment('自动回复用语');
            $table->text('menu_json')->comment('菜单栏');
            $table->timestamps();

        }
        );

        Schema::create(
            'official_activity', function( Blueprint $table ) {
            $table->increments('id');
            $table->string('name')->comment('活动名称');
            $table->string('qr_code_url')->comment('活动入口二维码'); //即输入电话号码的那一步
            $table->text('default_welcome')->comment('默认欢迎语');
            $table->text('be_recommend_welcome')->comment('被推荐获得抽奖码欢迎语');
            $table->text('auto_reply_welcome')->default("已收到您的留言，请稍等")->comment('自动回复用语');
            $table->dateTime("start_time")->index()->comment('活动开始时间');
            $table->dateTime("end_time")->comment('活动结束时间');
            $table->string("poster_base_img_url")->comment('海报背景图');
            $table->tinyInteger("is_active")->default(0)->comment('活动是否激活');
            $table->timestamps();
            $table->softDeletes();
        }
        );

        Schema::create(
            'official_activity_user', function( Blueprint $table ) {
            $table->increments('id');
            $table->integer('official_activity_id')->unsigned();
            //$table->foreign('official_activity_id')->references('id')->on('official_activity');

            $table->string("open_id")->index()->comment('用户open_id');
            $table->string("parent_open_id")->index()->default(0)->comment('from 某个用户open_id');
            $table->string("phone", 11)->index()->comment('用户联系方式');
            $table->integer("draw_number_count")->index()->default(0)->comment('用户抽奖码个数');
            $table->tinyInteger("status")->default(0)->comment('用户状态');
            $table->string("poster_url")->default('')->comment('海报地址');
            $table->string("poster_media_id")->default('')->comment("media_id");
            $table->timestamps();
        }
        );
        Schema::create(
            'official_activity_number', function( Blueprint $table ) {
            $table->increments('id');
            $table->integer('official_activity_id')->unsigned();
            //$table->foreign('official_activity_id')->references('id')->on('official_activity');

            $table->string("open_id")->index()->comment('参与用户open_id');
            $table->string("children_open_id")->index()->default(0)->comment('from 某个用户id');
            $table->string("draw_number")->unique()->comment('用户抽奖码');
            $table->timestamps();
        }
        );

        // 分享表
        Schema::create(
            'official_activity_share_relation', function( Blueprint $table ) {
            $table->increments('id');
            $table->integer('official_activity_id')->unsigned();
           // $table->foreign('official_activity_id')->references('id')->on('official_activity');

            $table->string("open_id")->index()->comment('open_id');
            $table->string("parent_open_id")->index()->default(0)->comment('from 某个用户id');
            $table->timestamps();
        }
        );


        Schema::create(
            'official_activity_share_setting', function( Blueprint $table ) {
            $table->increments('id');
            $table->integer('official_activity_id')->unsigned();
            //$table->foreign('official_activity_id')->references('id')->on('official_activity');

            $table->string('title')->comment('分享标题');
            $table->string('desc')->comment('描述');
            $table->string('link_url')->comment('分享地址');
            $table->string('cover_img')->comment('分享图片');
            $table->tinyInteger('type')->index()->default(0)->comment('类型:0(朋友圈),1(朋友)');
            $table->timestamps();
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
        Schema::dropIfExists('official_activity_user');
        Schema::dropIfExists('official_activity_number');
        Schema::dropIfExists('official_activity_share_relation');
        Schema::dropIfExists('official_activity');
        Schema::dropIfExists('official_account_default_setting');
        Schema::dropIfExists('official_activity_share_setting');
    }

}
