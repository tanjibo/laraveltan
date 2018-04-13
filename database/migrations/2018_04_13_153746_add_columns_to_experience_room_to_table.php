<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToExperienceRoomToTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('experience_room', function (Blueprint $table) {
            $table->integer("playday_price")->default(0)->comment('休息日');
            $table->integer("holiday_price")->default(0)->comment('节假日');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('experience_room', function (Blueprint $table) {
            $table->dropColumn([ 'playday_price','holiday_price']);
        });
    }
}
