<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExperienceRating extends Model
{
    protected $fillable = [
        'experience_id',
        'user_id',
        'rating',
        'comment',
    ];

    public function experience()
    {
        return $this->belongsTo(Experience::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
