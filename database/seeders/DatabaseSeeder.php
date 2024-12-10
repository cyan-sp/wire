<?php

namespace Database\Seeders;

use App\Models\Plan;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Call other seeders
        $this->call([
            CompanySeeder::class,
            BrandSeeder::class,
            PlanSeeder::class, // Add the PlanSeeder
        ]);
        // Create the user
        // Get the first plan
        $firstPlan = Plan::first();

        // Create the user 'cyan' and attach the first plan
        $cyanUser = User::factory()->create([
            'name' => 'cyan',
            'email' => 'cyan.mv@gmail.com',
            'password' => Hash::make('toast'),
        ]);

        // Attach the first plan to the 'cyan' user
        $cyanUser->plans()->attach($firstPlan->id);

        $user = User::factory()->create([
           'name' => 'clementine',
           'email' => 'clementine@gmail.com',
           'password' => Hash::make('toast'),
        ]);

        // Directly create the admin record
        DB::table('admins')->insert([
            'id' => $user->id, // Match the user's ID
            'role' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);


    }
}
