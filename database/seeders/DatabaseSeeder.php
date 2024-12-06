<?php

namespace Database\Seeders;

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
        // Create the user
        $user = User::factory()->create([
            'name' => 'cyan',
            'email' => 'cyan.mv@gmail.com',
            'password' => Hash::make('toast'),
        ]);

        // Directly create the admin record
        DB::table('admins')->insert([
            'id' => $user->id, // Match the user's ID
            'role' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Call other seeders
        $this->call([
            CompanySeeder::class,
            BrandSeeder::class,
            PlanSeeder::class, // Add the PlanSeeder
        ]);
    }
}
