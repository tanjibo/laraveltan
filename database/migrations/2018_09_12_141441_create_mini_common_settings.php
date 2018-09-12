<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMiniCommonSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mini_common_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->tinyInteger('mini_type')->index();
            $table->string('navigation_bar_color',10)->default('');
            $table->string('banner_url',200)->default('');
            $table->string('common_color',10)->default('')->comment('整体色系');
            $table->string('text')->default('');
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
        Schema::dropIfExists('mini_common_settings');
    }
}
