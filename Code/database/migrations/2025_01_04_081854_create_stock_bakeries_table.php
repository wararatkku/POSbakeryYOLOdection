<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('stock_bakeries', function (Blueprint $table) {
            $table->id("StockBakery_ID");
            $table->integer('Bakery_quantity');
            $table->integer('Sell_quantity');
            $table->date('Bakery_exp');
            $table->string("Bakery_ID");
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_bakeries', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::dropIfExists('stock_bakeries');
    }
};
