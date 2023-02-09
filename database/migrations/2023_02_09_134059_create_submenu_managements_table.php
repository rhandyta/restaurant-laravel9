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
        Schema::create('submenu_managements', function (Blueprint $table) {
            $table->id();
            $table->string('role')->nullable();
            $table->foreignId('menu_id')->references('id')->on('menu_managements')->cascadeOnUpdate();
            $table->string('label_submenu');
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
        Schema::dropIfExists('submenu_managements');
    }
};
