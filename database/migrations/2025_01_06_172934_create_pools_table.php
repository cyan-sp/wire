<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pools', function (Blueprint $table) {
            $table->id();
            // Remove the direct plan_id relationship since we'll use the pivot table
            $table->unsignedInteger('coupon_limit')
                  ->comment('Maximum number of coupons that can be created in this pool');
            $table->unsignedInteger('coupons_used')
                  ->default(0)
                  ->comment('Number of coupons currently created from this pool');
            $table->timestamp('starts_at')
                  ->comment('When this pool becomes available for coupon creation');
            $table->timestamp('expires_at')
                  ->comment('When this pool expires and can no longer be used');
            $table->boolean('status')
                  ->default(true)
                  ->comment('Whether this pool is active');
            $table->timestamps();
        });

        Schema::create('pool_plan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pool_id')
                  ->unique() // This constraint ensures each pool belongs to only one plan
                  ->constrained()
                  ->onDelete('cascade')
                  ->comment('Reference to the pool - unique to enforce one-to-many relationship');
            $table->foreignId('plan_id')
                  ->constrained()
                  ->onDelete('cascade')
                  ->comment('Reference to the plan');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pool_plan');
        Schema::dropIfExists('pools');
    }
};
