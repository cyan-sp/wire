<?php

namespace Database\Seeders;

use App\Models\Plan;
use App\Models\Pool;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PoolSeeder extends Seeder
{
    /**
     * Run the database seeds to create test pools with various states.
     * This seeder demonstrates different pool scenarios including:
     * - Active pools with available coupons
     * - Fully utilized pools
     * - Expired pools
     * - Future pools
     */
    public function run(): void
    {
        // Let's get our existing plans to attach pools to them
        $hutAndCluckPlan = Plan::where('name', 'Hut & Cluck')->first();
        $tacoPlan = Plan::where('name', 'Taco Plan')->first();
        $silverPlan = Plan::where('name', 'Silver Plan')->first();

        // Create pools using a transaction to ensure data integrity
        DB::transaction(function () use ($hutAndCluckPlan, $tacoPlan, $silverPlan) {
            // Create an active pool for Hut & Cluck with some coupons used
            $activePool = Pool::create([
                'coupon_limit' => 100,
                'coupons_used' => 2, // Since we have existing coupons
                'starts_at' => Carbon::now()->subDays(15),
                'expires_at' => Carbon::now()->addMonths(3),
                'status' => true
            ]);
            
            // Attach to Hut & Cluck plan through pivot table
            $hutAndCluckPlan->pools()->attach($activePool->id);

            // Create a fully utilized pool for Hut & Cluck
            $fullyUtilizedPool = Pool::create([
                'coupon_limit' => 50,
                'coupons_used' => 0,
                'starts_at' => Carbon::now()->subMonths(2),
                'expires_at' => Carbon::now()->addMonth(),
                'status' => true // Automatically set to false since it's fully utilized
            ]);
            
            $hutAndCluckPlan->pools()->attach($fullyUtilizedPool->id);

            // Create an expired pool for Taco Plan
            $expiredPool = Pool::create([
                'coupon_limit' => 75,
                'coupons_used' => 45,
                // 'starts_at' => Carbon::now()->subMonths(6),
                // 'expires_at' => Carbon::now()->subMonth(),
                'starts_at' => Carbon::now(),
                'expires_at' => Carbon::now()->addMonths(7),
                'status' => true
            ]);
            
            $tacoPlan->pools()->attach($expiredPool->id);

            // Create a future pool for Silver Plan
            $futurePool = Pool::create([
                'coupon_limit' => 200,
                'coupons_used' => 0,
                // 'starts_at' => Carbon::now()->addMonth(),
                'starts_at' => Carbon::now(),
                'expires_at' => Carbon::now()->addMonths(7),
                'status' => true
            ]);
            
            $silverPlan->pools()->attach($futurePool->id);

            // Create an active pool with no coupons used yet for Silver Plan
            $freshPool = Pool::create([
                'coupon_limit' => 150,
                'coupons_used' => 0,
                'starts_at' => Carbon::now(),
                'expires_at' => Carbon::now()->addMonths(6),
                'status' => true
            ]);
            
            $silverPlan->pools()->attach($freshPool->id);
        });
    }
}
