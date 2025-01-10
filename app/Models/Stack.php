<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Stack extends Model
{
    protected $fillable = [
        'card_limit',
        'cards_used',
        'status'
    ];

    protected $casts = [
        'status' => 'boolean'
    ];

    // Check if this stack can still issue cards
    public function isAvailable(): bool
    {
        // A stack is available if it's active and hasn't reached its limit
        return $this->status && $this->cards_used < $this->card_limit;
    }

    // Calculate how many more cards can be issued
    public function getAvailableCardsAttribute(): int
    {
        // If the stack is available, return remaining capacity, otherwise 0
        return $this->isAvailable() 
            ? ($this->card_limit - $this->cards_used) 
            : 0;
    }

    // Relationship with Plan
    public function plan(): BelongsToMany
    {
        return $this->belongsToMany(Plan::class, 'stack_plan')
                    ->withTimestamps();
    }
}
