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
        Schema::create('menu_managements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('labelmenu_id')->references('id')->on('labelmenu_managements')->cascadeOnUpdate();
            $table->string('role')->nullable();
            $table->string('label_menu');
            $table->string('path');
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
        Schema::dropIfExists('menu_managements');
    }
};
