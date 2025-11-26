<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'category',
        'description',
        'price',
        'duration',
        'terms',
        'latitude',
        'longitude',
        'address',
        'max_people',
        'status',
        'rating',
        'reviews',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function addons()
    {
        return $this->hasMany(ExperienceAddon::class);
    }

    public function media()
    {
        return $this->hasMany(ExperienceMedia::class);
    }

    public function timeSlots()
    {
        return $this->hasMany(ExperienceTimeSlot::class);
    }

    public function ratings()
{
    return $this->hasMany(ExperienceRating::class);
}
}

