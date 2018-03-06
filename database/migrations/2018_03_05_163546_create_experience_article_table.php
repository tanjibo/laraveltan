<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExperienceArticleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('experience_article', function (Blueprint $table) {
            $table->increments('id');
            $table->string("title",50)->index()->default('')->comment('文章标题');
            $table->string('author',10)->default('了如三舍')->comment('文章作者');
            $table->integer('parent_id')->default(0)->comment('父级id');
            $table->smallInteger("type")->default(1)->index()->comment('文章类型：0 三舍栏目 1:发现栏目顶级文章 2：发现栏目子级文章');
            $table->text('content')->comment('文章正文');
            $table->string("desc",150)->default('')->comment('文章简介');
            $table->integer("view_count")->default(1)->comment('文章浏览数');
            $table->integer("comment_count")->default(1)->comment('文章浏览数');
            $table->integer("like_count")->default(1)->comment("文章喜欢数量");
            $table->string("cover_img")->nullable()->comment('文章封面图片');
            $table->smallInteger("sorts")->default(1)->comment('文章排序');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('experience_article');
    }
}
