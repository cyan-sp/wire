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

    // Relationship with Brand model
//    public function brand()
//    {
//        return $this->belongsTo(Brand::class);
//    }
}
