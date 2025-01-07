<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'type',
        'name', // Replacing 'title' with 'name'
        'description',
        'start_date',
        'end_date',
        'redeem_at',
        'image',
    ];

    // public function plans()
    // {
    //     return $this->belongsToMany(Plan::class, 'coupon_plan', 'coupon_id', 'plan_id');
    // }
     public function plans()
    {
        return $this->belongsToMany(Plan::class, 'coupon_plan', 'coupon_id', 'plan_id')
                    ->withTimestamps();
    }
    
    public function plan()
    {
        //        return $this->belongsTo(Plan::class, 'plan_id');
        return $this->belongsToMany(Plan::class, 'coupon_plan', 'coupon_id', 'plan_id')->limit(1);
    }
    
    
    // public function plan()
//     {
// //        return $this->belongsTo(Plan::class, 'plan_id');
//         return $this->belongsToMany(Plan::class, 'brand_plan', 'brand_id', 'plan_id')->limit(1);
//     }

//     // Relationship with Plans
//     public function plans()
//     {
//         return $this->belongsToMany(Plan::class, 'brand_plan', 'brand_id', 'plan_id')->withTimestamps();
//     }
}
