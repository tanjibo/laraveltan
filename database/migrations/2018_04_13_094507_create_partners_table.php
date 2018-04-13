<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePartnersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('experience_partners', function (Blueprint $table) {
            $table->increments('id');
            $table->string("name",20)->default('')->unique()->comment('合作伙伴');
            $table->string("token",40)->default('')->unique()->comment('随机唯一字符串');
            $table->string("mini_url")->default("")->comment('小程序地址');
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
        Schema::dropIfExists('experience_partners');
    }
}
