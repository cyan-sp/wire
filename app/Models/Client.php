<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'email',
    ];
    public function plans()
    {
        return $this->belongsToMany(Plan::class, 'client_plan', 'client_id', 'plan_id')
            ->withTimestamps(); // Optional: Tracks created_at and updated_at
    }

//     public function plans()
// {
//     return $this->belongsToMany(Plan::class, 'plan_client', 'client_id', 'plan_id')
//         ->withTimestamps(); // Ensure correct foreign key order
// }

    public function user()
    {
        return $this->morphOne(User::class, 'userable'); // Polymorphic relation
    }

    // Accessor to retrieve the name from the User model
    public function getNameAttribute()
    {
        return $this->user->name ?? null;
    }

    public function plan()
    {
//        return $this->belongsTo(Plan::class, 'plan_id');
        return $this->belongsToMany(Plan::class, 'client_plan', 'client_id', 'plan_id')->limit(1);
    }
}
