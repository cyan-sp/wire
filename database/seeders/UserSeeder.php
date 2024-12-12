<?php

namespace Database\Seeders;

use App\Models\Plan;
use App\Models\User;
use App\Models\Client;
use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define users and their relationships
        $users = [
            [
                'name' => 'cyan',
                'email' => 'cyan.mv@gmail.com',
                'password' => Hash::make('toast'),
                'admin' => [
                    'role' => 'admin', // Role for the admin
                ],
                'client' => null, // No client relationship for this user
                'plans' => [1, 2], // Attach the first plan
            ],
            [
                'name' => 'mila',
                'email' => 'mila@gmail.com',
                'password' => Hash::make('toast'),
                'admin' => [
                    'role' => 'admin', // Role for the admin
                ],
                'client' => null, // No client relationship for this user
                'plans' => [3], // Attach the first plan
            ],
            [
                'name' => 'clementine',
                'email' => 'clementine@gmail.com',
                'password' => Hash::make('toast'),
                'client' => [
                    'name' => 'Clementine', // Explicitly set
                    'email' => 'clementine@gmail.com', // Explicitly set
                    'plans' => [1], // Attach the first plan
                ],
            ],
            [
                'name' => 'jenna',
                'email' => 'jennta@gmail.com',
                'password' => Hash::make('toast'),
                'client' => [
                    'name' => 'jenna', // Explicitly set
                    'email' => 'jenna@gmail.com', // Explicitly set
                    'plans' => [1], // Attach the first plan
                ],
            ],
            [
                'name' => 'marina',
                'email' => 'marina@gmail.com',
                'password' => Hash::make('toast'),
                'client' => [
                    'name' => 'marina', // Explicitly set
                    'email' => 'marina@gmail.com', // Explicitly set
                    'plans' => [1, 2], // Attach the first plan
                ],
            ],
        ];

        foreach ($users as $userData) {
            // Extract and unset admin, client, and plans data before creating the user
            $adminData = $userData['admin'] ?? null;
            $clientData = $userData['client'] ?? null;
            $userPlans = $userData['plans'] ?? [];
            unset($userData['admin'], $userData['client'], $userData['plans']);

            // Create the user
            $user = User::create($userData);
//            dump("User created: ", $user->toArray());

            // Handle the client relationship
            if ($clientData) {
                $clientPlans = $clientData['plans'] ?? [];
                unset($clientData['plans']);

                $clientData['name'] = $clientData['name'] ?? $userData['name'];
                $clientData['email'] = $clientData['email'] ?? $userData['email'];

                $client = Client::create($clientData);
//                dump("Client created: ", $client->toArray());

                $user->userable()->associate($client);
                $user->save();

                // Attach plans to the client
                if (!empty($clientPlans)) {
//                    dump("Attaching plans to client: ", $clientPlans);
                    $client->plans()->sync($clientPlans);
                }
            }

            // Attach plans to the user
            if (!empty($userPlans)) {
//                dump("Attaching plans to user: ", $userPlans);
                $user->plans()->sync($userPlans);
            }
        }
    }
}
