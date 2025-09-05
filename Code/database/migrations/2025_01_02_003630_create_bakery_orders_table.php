<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBakeryOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     */
    public function up(): void
    {
        Schema::create('bakery_orders', function (Blueprint $table) {
            $table->id("BakeryOrder_ID");
            $table->integer("Total_price");
            $table->bigInteger("Payment_ID")->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bakery_orders', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::dropIfExists('bakery_orders');
    }
}