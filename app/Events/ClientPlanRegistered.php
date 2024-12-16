<?php

namespace App\Events;

use App\Models\Client; // Ensure this points to the correct namespace
use App\Models\Plan;   // Ensure this points to the correct namespace
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ClientPlanRegistered
{
    use Dispatchable, SerializesModels;

    public $client;
    public $plan;

    public function __construct(Client $client, Plan $plan)
    {
        $this->client = $client;
        $this->plan = $plan;
    }
}
