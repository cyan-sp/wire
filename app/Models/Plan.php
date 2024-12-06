<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'description',
        'prefix',
        'status',
        'consecutive_length',
        'color',
        'image',
        'current_sequence',
    ];

}
