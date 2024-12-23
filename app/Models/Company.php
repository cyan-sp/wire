<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_name',
        'legal_name',
        'tax_id',
        'phone',
        'address',
        'email',
        'website',
        'city',
        'state',
        'country',
        'status',
        'logo',
        'brand_id',
    ];

     public function brands()
    {
        return $this->hasMany(Brand::class);
    }

    // public function managers()
    // {
    //     return $this->belongsToMany(User::class, 'user_company')
    //         ->withPivot('role', 'permissions')
    //         ->withTimestamps();
    // }
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_company');
    }

    
    // Relationship with Brand model
//    public function brand()
//    {
//        return $this->belongsTo(Brand::class);
//    }
}
