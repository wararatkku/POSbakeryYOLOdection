<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBakeriesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('bakeries', function (Blueprint $table) {
            $table->id('Bakery_ID');
            $table->string('Bakery_name');
            $table->string('Bakery_name_en');
            $table->string('Bakery_image');
            $table->integer('Bakery_price');
            $table->integer('IP_status')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('bakeries', function (Blueprint $table){
            $table->dropSoftDeletes();

        });
        Schema::dropIfExists('bakeries');
    }
}