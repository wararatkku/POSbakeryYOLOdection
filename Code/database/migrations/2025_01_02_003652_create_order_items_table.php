<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     */
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id("OrderItem_ID");
            $table->string("BakeryOrder_ID");
            $table->string("Bakery_ID");
            $table->integer("Sum_quantity");
            $table->integer("Sum_price");
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::dropIfExists('order_items');
    }
}