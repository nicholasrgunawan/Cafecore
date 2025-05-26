<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenuBahanTable extends Migration
{
    public function up()
    {
        Schema::create('menu_bahan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('menu_id'); // Make sure it's unsigned
            $table->unsignedBigInteger('bahan_id'); // Make sure it's unsigned
            $table->timestamps();

            // Adding the foreign key constraints
            $table->foreign('menu_id')->references('id')->on('menus')->onDelete('cascade');
            $table->foreign('bahan_id')->references('id')->on('bahans')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('menu_bahan');
    }
}
