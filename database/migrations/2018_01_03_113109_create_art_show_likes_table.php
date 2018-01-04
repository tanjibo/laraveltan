<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArtShowLikesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('art_show_likes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->index()->unsigned()->default(0);
            $table->integer('likable_id')->unsigned()->default(0);
            $table->string('likable_type')->index();
            $table->string('is')->index();
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
        Schema::dropIfExists('art_show_likes');
    }
}
