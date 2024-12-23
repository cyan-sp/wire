<?php

namespace Database\Seeders;

use App\Models\Plan;
use App\Models\User;
use App\Models\Client;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'cyan',
                'email' => 'cyan.mv@gmail.com',
                'password' => Hash::make('toast'),
                'company' => 1, // Yum brands ID
                'client' => null,
            ],
            [
                'name' => 'mila',
                'email' => 'mila@gmail.com',
                'password' => Hash::make('toast'),
                'company' => 2, // Jollibee Foods ID
                'client' => null,
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
            // ... other client users remain the same
        ];

        foreach ($users as $userData) {
            $companyId = $userData['company'] ?? null;
            $clientData = $userData['client'] ?? null;
            unset($userData['company'], $userData['client']);

            $user = User::create($userData);

            // Handle company association for managers
            if ($companyId) {
                $user->company()->attach($companyId);
            }

            // Handle client data and plan associations
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
                        $sequence = str_pad(
                            $plan->current_sequence + 1, 
                            $plan->consecutive_length, 
                            '0', 
                            STR_PAD_LEFT
                        );
                        $numbering = "{$plan->code}{$plan->prefix}{$sequence}";

                        // Attach with numbering
                        $client->plans()->attach($planId, [
                            'numbering' => $numbering
                        ]);

                        // Increment the plan's sequence
                        $plan->increment('current_sequence');
                    }
                }
            }
        }
    }
}
