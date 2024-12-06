<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model; // Add this line
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Admin extends Model
{
    protected $fillable = ['role'];

    public function user(): MorphOne
    {
        return $this->morphOne(User::class, 'userable');
    }
}
