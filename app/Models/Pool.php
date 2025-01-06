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
}
