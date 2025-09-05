<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductbuyTable extends Migration
{

    public function up()
    {
        Schema::create('productsbuy', function (Blueprint $table) {
            $table->id('Product_ID');
            $table->string('Product_Name');
            $table->integer('Product_price');
            $table->integer('Product_quantity');
            $table->string('Product_image');
            $table->string('Product_unit');
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
        Schema::table('productsbuy', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::dropIfExists('productsbuy');
    }
}