<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('stacks', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('card_limit')
                ->comment('Maximum number of cards that can be issued from this stack');
            $table->unsignedInteger('cards_used')
                ->default(0)
                ->comment('How many cards have been issued so far');
            $table->boolean('status')
                ->default(true)
                ->comment('Whether this stack is active');
            $table->timestamps();
        });

        Schema::create('stack_plan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stack_id')
                ->unique()  // Each stack belongs to one plan only
                ->constrained()
                ->onDelete('cascade');
            $table->foreignId('plan_id')
                ->constrained()
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stacks');
    }
};
