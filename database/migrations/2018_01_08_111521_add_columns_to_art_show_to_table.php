<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToArtShowToTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('art_show', function (Blueprint $table) {
            $table->smallInteger('status')->index()->default(1)->comment('显示隐藏');
            $table->string('mini_route')->default('')->comment('小程序路径');
            $table->string('sorting')->default(100)->index()->comment('排序');
            $table->string('share_count')->default(0)->index()->comment('排序');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('art_show', function (Blueprint $table) {
            $table->dropColumn([ 'status','mini_route','sorting','share_count']);
        });
    }
}
