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
    $this->call([
        CompanySeeder::class,
        BrandSeeder::class,
        PlanSeeder::class,
        UserSeeder::class,
        CouponSeeder::class

    ]);

    // $firstPlan = Plan::first();

    // if (!$firstPlan) {
    //     dd('No plans exist in the database. Check your PlanSeeder.');
    // }

    // // Create the user 'cyan' and attach the first plan
    // $cyanUser = User::factory()->create([
    //     'name' => 'cyan',
    //     'email' => 'cyan.mv@gmail.com',
    //     'password' => Hash::make('toast'),
    // ]);
    // dump('Cyan User:', $cyanUser->toArray());
    // $cyanUser->plans()->attach($firstPlan->id);

    // // Create Clementine
    // $clementineUser = User::create([
    //     'name' => 'clementine',
    //     'email' => 'clementine@gmail.com',
    //     'password' => Hash::make('toast'),
    // ]);
    // $clementineClient = Client::create([
    //     'name' => 'Clementine',
    //     'email' => 'clementine@gmail.com',
    // ]);
    // $clementineUser->userable()->associate($clementineClient);
    // $clementineUser->save();
    // dump('Clementine Client:', $clementineClient->toArray());
    // dump('Attaching Plan ID:', $firstPlan->id, 'to Client ID:', $clementineClient->id);
    // $clementineClient->plans()->attach($firstPlan->id);

    // // Create Jenna
    // $jennaUser = User::create([
    //     'name' => 'jenna',
    //     'email' => 'jenna@gmail.com',
    //     'password' => Hash::make('toast'),
    // ]);
    // $jennaClient = Client::create([
    //     'name' => 'jenna',
    //     'email' => 'jenna@gmail.com',
    // ]);
    // $jennaUser->userable()->associate($jennaClient);
    // $jennaUser->save();
    // dump('Jenna Client:', $jennaClient->toArray());
    // dump('Attaching Plan ID:', $firstPlan->id, 'to Client ID:', $jennaClient->id);
    // $jennaClient->plans()->attach($firstPlan->id);

    // // Add admin for Clementine
    // DB::table('admins')->insert([
    //     'id' => $clementineUser->id,
    //     'role' => 'admin',
    //     'created_at' => now(),
    //     'updated_at' => now(),
    // ]);
}
}
