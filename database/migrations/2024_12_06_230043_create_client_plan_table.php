<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientPlanTable extends Migration
{
    public function up()
    {
        Schema::create('client_plan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->foreignId('plan_id')->constrained()->onDelete('cascade');
            $table->string('numbering')->unique();
            $table->timestamps(); // Optional: Enable if you want `withTimestamps`

            $table->unique(['client_id', 'plan_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('client_plan');
    }
}
