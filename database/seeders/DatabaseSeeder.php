<?php

namespace Database\Seeders;

use App\Models\Plan;
use App\Models\User;
use App\Models\Client;
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
            PlanSeeder::class,
        ]);

        // Get the first plan
        $firstPlan = Plan::first();

        // Create the user 'cyan' and attach the first plan
        $cyanUser = User::factory()->create([
            'name' => 'cyan',
            'email' => 'cyan.mv@gmail.com',
            'password' => Hash::make('toast'),
        ]);

        $cyanUser->plans()->attach($firstPlan->id);

        // Create Clementine as a user and client
        $clementineUser = User::create([
            'name' => 'clementine',
            'email' => 'clementine@gmail.com',
            'password' => Hash::make('toast'),
        ]);

        // Create a Client record and associate it with Clementine
        $clementineClient = Client::create([
            'name' => 'Clementine',
            'email' => 'clementine@gmail.com',
        ]);

        $clementineUser->userable()->associate($clementineClient);
        $clementineUser->save();

        // Associate Clementine with the first plan
        $clementineClient->plans()->attach($firstPlan->id);

        // Directly create an admin record for Clementine if needed
        DB::table('admins')->insert([
            'id' => $clementineUser->id,
            'role' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
