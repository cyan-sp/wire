<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\DB;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'prefix',
        'status',
        'consecutive_length',
        'color',
        'image',
        'current_sequence',
    ];

    public function clients()
    {
        return $this->belongsToMany(Client::class, 'client_plan', 'client_id', 'plan_id')
            ->withTimestamps(); // Optional: Tracks created_at and updated_at
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'plan_user', 'plan_id', 'user_id')->withTimestamps();
    }

    // Relationship with Brands
    public function brands()
    {
        return $this->belongsToMany(Brand::class, 'brand_plan', 'plan_id', 'brand_id')->withTimestamps();
    }

    // public function coupons()
    // {
    //     return $this->belongsToMany(Coupon::class, 'coupon_plan', 'coupon_id', 'plan_id')->withTimestamps();
    // }
    public function coupons()
    {
        return $this->belongsToMany(Coupon::class, 'coupon_plan', 'plan_id', 'coupon_id')
            ->withTimestamps();
    }

    public function pools(): BelongsToMany
    {
        return $this->belongsToMany(Pool::class, 'pool_plan')
            ->withTimestamps();
    }

    public function createPool(array $poolData)
    {
        $pool = Pool::create($poolData);

        // Attach the pool to this plan
        $this->pools()->attach($pool->id);

        return $pool;
    }

    // Existing attributes and relationships...

    /**
     * Get all active pools for this plan that can still create coupons.
     * This includes checking dates and coupon limits.
     */
    public function getActivePools()
    {
        // Add explicit status check and logging
        $pools = $this->pools()
            ->where('status', true)  // Make sure this is being applied
            ->where('starts_at', '<=', now())
            ->where('expires_at', '>', now())
            ->where('coupons_used', '<', DB::raw('coupon_limit'))
            ->get();

        \Log::info('Retrieved active pools for plan', [
            'plan_id' => $this->id,
            'plan_name' => $this->name,
            'pool_count' => $pools->count(),
            'pools' => $pools->toArray()
        ]);

        return $pools;
    }

    public function getAvailableCouponsAttribute(): int
    {
        $total = $this->getActivePools()
            ->sum(function ($pool) {
                return $pool->coupon_limit - $pool->coupons_used;
            });

        \Log::info('Calculated total available coupons for plan', [
            'plan_id' => $this->id,
            'plan_name' => $this->name,
            'total_available' => $total
        ]);

        return $total;
    }

    /**
     * Check if this plan can create more coupons.
     */
    public function canCreateCoupons(): bool
    {
        return $this->available_coupons > 0;
    }

    // ... existing code ...

    public function stacks(): BelongsToMany
    {
        return $this->belongsToMany(Stack::class, 'stack_plan')
            ->withTimestamps();
    }

    // Get all stacks that can still issue cards
    public function getActiveStacks()
    {
        return $this->stacks()
            ->where('status', true)
            ->where('cards_used', '<', DB::raw('card_limit'))
            ->get();
    }

    // Calculate total available cards across all stacks
    public function getAvailableCardsAttribute(): int
    {
        return $this->getActiveStacks()
            ->sum(function ($stack) {
                return $stack->card_limit - $stack->cards_used;
            });
    }

    // Check if this plan can issue more cards
    public function canIssueCards(): bool
    {
        return $this->available_cards > 0;
    }
}
