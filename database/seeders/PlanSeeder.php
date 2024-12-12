<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create plans
        $plan1 = Plan::create([
            'code' => '001',
            'name' => 'YUM Plan',
            'prefix' => '444',
            'status' => true,
            'consecutive_length' => 5,
            'color' => '#FF5733',
            'image' => 'KFC-logo.webp',
            'current_sequence' => 0,
        ]);

        $plan2 = Plan::create([
            'code' => '999',
            'name' => 'Darden restaurants plan',
            'prefix' => '333',
            'status' => true,
            'consecutive_length' => 5,
            'color' => '#FF5733',
            'image' => '',
            'current_sequence' => 0,
        ]);

        $brand1 = Brand::find(1); // KFC
        $brand1->plans()->attach([$plan1->id]);

        $brand2 = Brand::find(2);
        $brand2->plans()->attach([$plan1->id]);

        $brand3 = Brand::find(3);
        $brand3->plans()->attach([$plan2->id]);


    }
}
