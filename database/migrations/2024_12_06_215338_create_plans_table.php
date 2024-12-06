<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlansTable extends Migration
{
    public function up()
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('code', 3); // '001' to '999'
            $table->string('description');
            $table->string('prefix', 3); // Similar to 'code'
            $table->boolean('status')->default(true); // Active or inactive status
            $table->unsignedTinyInteger('consecutive_length')->default(5); // Number from 5 to 10
            $table->string('color')->nullable(); // Hex color or CSS color name
            $table->string('image')->nullable(); // Path to the image file
            $table->unsignedBigInteger('current_sequence')->default(0); // Add this column
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('plans');
    }
}
