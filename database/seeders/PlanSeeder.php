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
            'code' => '222',
            'name' => 'Hut & Cluck',
            'prefix' => '444',
            'status' => true,
            'consecutive_length' => 5,
            'color' => '#FF5733',
            'image' => 'KFC-logo.webp',
            'current_sequence' => 0,
        ]);

        $plan2 = Plan::create([
            'code' => '999',
            'name' => 'Taco Plan',
            'prefix' => '333',
            'status' => true,
            'consecutive_length' => 5,
            'color' => '#FF5733',
            'image' => '',
            'current_sequence' => 0,
        ]);

        $plan3 = Plan::create([
            'code' => '010',
            'name' => 'Silver Plan',
            'prefix' => '777',
            'status' => true,
            'consecutive_length' => 5,
            'color' => '#FF5733',
            'image' => '',
            'current_sequence' => 0,
        ]);

        $plan4 = Plan::create([
            'code' => '643',
            'name' => 'Golden Plan',
            'prefix' => '270',
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

        $brand4 = Brand::find(4);
        $brand4->plans()->attach([$plan3->id]);
        $brand4->plans()->attach([$plan4->id]);

        // $brand4 = Brand::find(4);
    }
}
