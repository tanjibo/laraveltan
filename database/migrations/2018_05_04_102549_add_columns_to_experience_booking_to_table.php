<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToExperienceBookingToTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('experience_booking', function (Blueprint $table) {
            $table->tinyInteger("is_refund")->default(0)->comment('是否已退款');
            $table->index("is_refund");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('experience_booking', function (Blueprint $table) {
            $table->dropColumn(['is_refund']);
            $table->dropIndex("is_refund");
        });
    }
}
