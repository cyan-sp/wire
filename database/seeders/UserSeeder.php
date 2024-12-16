<?php

namespace Database\Seeders;

use App\Models\Plan;
use App\Models\User;
use App\Models\Client;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'cyan',
                'email' => 'cyan.mv@gmail.com',
                'password' => Hash::make('toast'),
                'admin' => [
                    'role' => 'admin',
                ],
                'client' => null,
                'plans' => [1, 2],
            ],
            [
                'name' => 'mila',
                'email' => 'mila@gmail.com',
                'password' => Hash::make('toast'),
                'admin' => [
                    'role' => 'admin',
                ],
                'client' => null,
                'plans' => [3],
            ],
            [
                'name' => 'clementine',
                'email' => 'clementine@gmail.com',
                'password' => Hash::make('toast'),
                'client' => [
                    'name' => 'Clementine',
                    'email' => 'clementine@gmail.com',
                    'plans' => [1],
                ],
            ],
            [
                'name' => 'jenna',
                'email' => 'jenna@gmail.com',
                'password' => Hash::make('toast'),
                'client' => [
                    'name' => 'jenna',
                    'email' => 'jenna@gmail.com',
                    'plans' => [1],
                ],
            ],
            [
                'name' => 'marina',
                'email' => 'marina@gmail.com',
                'password' => Hash::make('toast'),
                'client' => [
                    'name' => 'marina',
                    'email' => 'marina@gmail.com',
                    'plans' => [1, 2],
                ],
            ],
        ];

        foreach ($users as $userData) {
            $adminData = $userData['admin'] ?? null;
            $clientData = $userData['client'] ?? null;
            $userPlans = $userData['plans'] ?? [];
            unset($userData['admin'], $userData['client'], $userData['plans']);

            $user = User::create($userData);

            if ($clientData) {
                $clientPlans = $clientData['plans'] ?? [];
                unset($clientData['plans']);

                $clientData['name'] = $clientData['name'] ?? $userData['name'];
                $clientData['email'] = $clientData['email'] ?? $userData['email'];

                $client = Client::create($clientData);
                $user->userable()->associate($client);
                $user->save();

                if (!empty($clientPlans)) {
                    foreach ($clientPlans as $planId) {
                        $plan = Plan::find($planId);

                        // Generate numbering
                        $sequence = str_pad($plan->current_sequence + 1, $plan->consecutive_length, '0', STR_PAD_LEFT);
                        $numbering = "{$plan->code}{$plan->prefix}{$sequence}";

                        // Attach with numbering
                        $client->plans()->attach($planId, ['numbering' => $numbering]);

                        // Increment the plan's sequence
                        $plan->current_sequence += 1;
                        $plan->save();
                    }
                }
            }

            if (!empty($userPlans)) {
                foreach ($userPlans as $planId) {
                    $user->plans()->attach($planId);
                }
            }
        }
    }
}
