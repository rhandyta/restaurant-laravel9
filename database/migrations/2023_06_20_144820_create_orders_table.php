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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_id')->unique();
            $table->foreignId('user_id')->references('id')->on('users')->cascadeOnUpdate();
            $table->string('transaction_id')->nullable();
            $table->string('transaction_code')->nullable();
            $table->string('transaction_status')->nullable();
            $table->text('transaction_message')->nullable();
            $table->string('payment_type');
            $table->string('bank')->nullable();
            $table->string('va_number')->nullable();
            $table->string('signature_key')->nullable();
            $table->string('gross_amount');
            $table->string('amount');
            $table->text('notes')->nullable();
            $table->string('discount')->nullable();
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
        Schema::dropIfExists('orders');
    }
};
