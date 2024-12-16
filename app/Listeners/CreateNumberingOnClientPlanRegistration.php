<?php

namespace App\Listeners;

use App\Events\ClientPlanRegistered;

class CreateNumberingOnClientPlanRegistration
{
    public function handle(ClientPlanRegistered $event)
    {
        $client = $event->client;
        $plan = $event->plan;

        // Generate numbering
        $sequence = str_pad($plan->current_sequence + 1, $plan->consecutive_length, '0', STR_PAD_LEFT);
        $numbering = "{$plan->code}{$plan->prefix}{$sequence}";

        // Save the numbering or log it
        // Assuming there's a pivot column `numbering` in the `client_plan` table
        $client->plans()->updateExistingPivot($plan->id, ['numbering' => $numbering]);

        // Increment the plan's sequence
        $plan->current_sequence += 1;
        $plan->save();
    }
}
