<?php

namespace Database\Seeders;

use App\Models\Plan;
use App\Models\Stack;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StackSeeder extends Seeder
{
    /**
     * Seeds the stacks table with test data demonstrating various scenarios.
     * Each plan gets different stack configurations to test card issuance limits.
     */
    public function run(): void
    {
        // First, let's get our test plans
        $hutAndCluckPlan = Plan::where('name', 'Hut & Cluck')->first();
        $tacoPlan = Plan::where('name', 'Taco Plan')->first();
        $silverPlan = Plan::where('name', 'Silver Plan')->first();

        // We'll use a transaction to ensure data consistency
        DB::transaction(function () use ($hutAndCluckPlan, $tacoPlan, $silverPlan) {
            // Create a partially used stack for Hut & Cluck
            // This simulates a stack that's been in use for a while
            $partialStack = Stack::create([
                'card_limit' => 100,   // Can issue up to 100 cards
                'cards_used' => 25,    // 25 cards already issued
                'status' => true       // Stack is still active
            ]);
            $hutAndCluckPlan->stacks()->attach($partialStack->id);

            // Create a second stack for Hut & Cluck that's almost full
            // This helps test multiple active stacks scenario
            $almostFullStack = Stack::create([
                'card_limit' => 50,    // Smaller stack size
                'cards_used' => 48,    // Only 2 cards left
                'status' => true       // Still active
            ]);
            $hutAndCluckPlan->stacks()->attach($almostFullStack->id);

            // Create a completely used stack for Taco Plan
            // This represents a depleted stack
            $usedStack = Stack::create([
                'card_limit' => 75,
                'cards_used' => 75,    // All cards used
                'status' => false      // Stack is inactive
            ]);
            $tacoPlan->stacks()->attach($usedStack->id);

            // Create a fresh stack for Silver Plan
            // This represents a brand new stack
            $freshStack = Stack::create([
                'card_limit' => 200,
                'cards_used' => 0,     // No cards used yet
                'status' => true       // Active and ready
            ]);
            $silverPlan->stacks()->attach($freshStack->id);

            // Create a backup stack for Silver Plan
            // This demonstrates having multiple stacks ready
            $backupStack = Stack::create([
                'card_limit' => 150,
                'cards_used' => 0,
                'status' => true
            ]);
            $silverPlan->stacks()->attach($backupStack->id);
        });
    }
}
