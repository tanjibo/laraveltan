<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArtShowCommentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('art_show_comment', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->index();
            $table->integer('art_show_id')->index();
            $table->integer('parent_id')->index()->default(0);
            $table->text('comment')->default('')->comment('正文内容');
            $table->boolean('status')->default(1)->comment('状态');
            $table->integer('like_count')->index()->default(0)->comment('点赞总数');
            $table->integer('reply_count')->index()->default(0)->comment('回复总数');
            $table->integer('to_be_reply_id')->default(0)->comment('被回复id');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('art_show_comment');
    }
}
