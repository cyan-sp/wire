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

    public function clients()
    {
        return $this->belongsToMany(Client::class, 'client_plan', 'client_id', 'plan_id')
            ->withTimestamps(); // Optional: Tracks created_at and updated_at
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'plan_user', 'plan_id', 'user_id')->withTimestamps();
    }


}
