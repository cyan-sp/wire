<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Plan::create([
            'code' => '001',
            'name' => 'VIP Group',
            'prefix' => '444',
            'status' => true,
            'consecutive_length' => 5,
            'color' => '#FF5733',
            'image' => 'KFC-logo.webp',
            'current_sequence' => 0, // Default value for sequence
        ]);
    }
}
