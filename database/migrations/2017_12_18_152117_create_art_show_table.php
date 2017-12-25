<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArtShowTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('art_show', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',100)->unique()->comment('名称');
            $table->string('attr')->default('')->comment('属性');
            $table->string('author_info')->default('')->comment('作者简介');
            $table->string('cover')->default('')->comment('封面图片');
            $table->text('introduction')->default('')->comment('描述');
            $table->string('desc')->default('')->comment('简介');
            $table->integer('comment_count')->default(0)->comment('评论总数');
            $table->integer('like_count')->default(0)->comment('点赞总数');
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
        Schema::dropIfExists('art_show');
    }
}
