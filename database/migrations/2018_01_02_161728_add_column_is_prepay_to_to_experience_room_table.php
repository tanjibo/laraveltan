<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnIsPrepayToToExperienceRoomTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('experience_room', function (Blueprint $table) {
            $table->boolean('is_prepay')->default(0)->comment('是否预付金支付');
            $table->smallInteger('prepay_percent')->default(100)->comment('预付金百分比');
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
           $table->dropColumn([ 'is_prepay','prepay_percent']);
        });
    }
}
