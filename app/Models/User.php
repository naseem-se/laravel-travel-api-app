<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function experiences()
    {
        return $this->hasMany(Experience::class, 'user_id');
    }

    public function ratings()
    {
        return $this->hasMany(ExperienceRating::class);
    }

    public function user_details()
    {
        return $this->hasOne(UserDetail::class);
    }

    // Ratings received from travelers
    public function ratingsReceived()
    {
        return $this->hasMany(UserRating::class, 'user_id');
    }

    // Ratings given by traveler
    public function ratingsGiven()
    {
        return $this->hasMany(UserRating::class, 'traveler_id');
    }

    // Average rating of the user (agency/guide)
    public function averageRating()
    {
        return $this->ratingsReceived()->avg('rating');
    }

}
