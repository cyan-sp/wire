<?php

namespace App\Listeners;

use App\Models\Client;
use App\Models\User; // Import the User model
use Illuminate\Auth\Events\Registered;

class CreateClientOnRegistration
{
    /**
     * Handle the event.h
     *
     * @param  \Illuminate\Auth\Events\Registered  $event
     * @return void
     */
    public function handle(Registered $event)
    {
        // Ensure the user is an instance of the User model
        $user = $event->user;

        if ($user instanceof User) {
            // Automatically create a client for the new user
            $client = Client::create([
                'name' => $user->name,
                'email' => $user->email,
            ]);

            // Associate the client with the user via the polymorphic relationship
            $user->userable_id = $client->id;
            $user->userable_type = Client::class;
            $user->save();
        }
    }
}
