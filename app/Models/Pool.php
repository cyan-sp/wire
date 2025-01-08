<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Pool extends Model
{
    protected $fillable = [
        'coupon_limit',
        'coupons_used',
        'starts_at',
        'expires_at',
        'status'
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'status' => 'boolean'
    ];

    public function plan(): BelongsToMany
    {
        // Even though we're using belongsToMany, our unique constraint ensures
        // this will only ever return one plan
        return $this->belongsToMany(Plan::class, 'pool_plan')
                    ->withTimestamps();
    }

    // Helper method to get the single associated plan
    public function getSinglePlan()
    {
        return $this->plan->first();
    }

    // Let's add methods to check pool availability
     public function isAvailable(): bool
    {
        // Let's add debug logging to see what's happening
        \Log::info('Checking pool availability', [
            'pool_id' => $this->id,
            'status' => $this->status,
            'starts_at' => $this->starts_at,
            'expires_at' => $this->expires_at,
            'coupons_used' => $this->coupons_used,
            'coupon_limit' => $this->coupon_limit
        ]);

        return $this->status 
            && $this->starts_at <= now()
            && $this->expires_at > now()
            && $this->coupons_used < $this->coupon_limit;
    }

    public function getAvailableCouponsAttribute(): int
    {
        // Add logging here too
        $available = $this->isAvailable() 
            ? ($this->coupon_limit - $this->coupons_used) 
            : 0;

        \Log::info('Calculated available coupons', [
            'pool_id' => $this->id,
            'available' => $available
        ]);

        return $available;
    }

    // Existing relationships
//    public function plan(): BelongsToMany
//    {
//        return $this->belongsToMany(Plan::class, 'pool_plan')
//            ->withTimestamps();
//    }
}
