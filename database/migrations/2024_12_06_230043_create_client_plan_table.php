<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientPlanTable extends Migration
{
    public function up()
    {
        Schema::create('plan_client', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plan_id')->constrained()->onDelete('cascade');
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->timestamps(); // Optional: Enable if you want `withTimestamps`
        });
    }

    public function down()
    {
        Schema::dropIfExists('plan_client');
    }
}
