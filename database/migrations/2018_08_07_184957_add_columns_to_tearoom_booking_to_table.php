<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToTearoomBookingToTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tearoom_booking', function (Blueprint $table) {
            $table->tinyInteger('is_prepay')->default(0)->comment('是否使用预付金');
            $table->tinyInteger('is_refund')->default(0)->comment('是否已经退款');
           $table->integer('balance')->default(0)->comment('余额支付');
           $table->tinyInteger('is_discount')->default(0)->comment('是否使用优惠券');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tearoom_booking', function (Blueprint $table) {
            $table->dropColumn(['is_prepay','is_refund','balance','is_discount']);
        });
    }
}
