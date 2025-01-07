<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
}
