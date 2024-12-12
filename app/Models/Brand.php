<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'company_id',
    ];

    // Relationship with Company
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
    public function plan()
    {
//        return $this->belongsTo(Plan::class, 'plan_id');
        return $this->belongsToMany(Plan::class, 'brand_plan', 'brand_id', 'plan_id')->limit(1);
    }

    // Relationship with Plans
    public function plans()
    {
        return $this->belongsToMany(Plan::class, 'brand_plan', 'brand_id', 'plan_id')->withTimestamps();
    }
}
