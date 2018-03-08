<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToExperienceArticleToTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('experience_article', function (Blueprint $table) {
            $table->smallInteger('status')->default(0)->comment('状态');
            $table->string('wechat_url')->nullable()->comment('微信地址');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('experience_article', function (Blueprint $table) {
            $table->dropColumn([ 'status','wechat_url']);
        });
    }
}
