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
        Schema::create('information_tables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_table_id')->references('id')->on('table_categories')->cascadeOnUpdate();
            $table->integer('seating_capacity');
            $table->enum('available', ['available', 'not available']);
            $table->string('location');
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
        Schema::dropIfExists('information_tables');
    }
};
