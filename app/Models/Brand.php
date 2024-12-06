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
}
