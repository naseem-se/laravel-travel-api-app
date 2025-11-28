<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserRating extends Model
{
    protected $fillable = [
        'user_id',
        'traveler_id',
        'rating',
        'review',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function traveler()
    {
        return $this->belongsTo(User::class, 'traveler_id');
    }
    
}
