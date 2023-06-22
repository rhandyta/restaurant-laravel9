<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_orders', function (Blueprint $table) {
            $table->string('order_id')->references('id')->on('orders')->cascadeOnUpdate();
            $table->integer('product_id');
            $table->string('product');
            $table->integer('quantity');
            $table->double('unit_price');
            $table->double('subtotal');
            $table->double('discount')->nullable();
            $table->double('total_price');
            $table->integer('rating')->nullable();
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
        Schema::dropIfExists('detail_orders');
    }
};
