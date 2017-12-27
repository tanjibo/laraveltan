<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArtShowSuggestionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('art_show_suggestion', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->index();
            $table->string('mobile',11)->nullable();
            $table->text('content');
            $table->text('reply')->default('');
            $table->smallInteger('status');
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
        Schema::dropIfExists('art_show_suggestion');
    }
}
