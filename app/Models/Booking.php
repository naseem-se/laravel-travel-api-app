<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'traveler_id','experience_id','start_at','guests',
        'subtotal','platform_fee','total','currency','status',
        'payment_provider','payment_provider_id','refund_provider_id'
    ];

    protected $casts = [
        'addons' => 'array',
        'start_at' => 'datetime',
        'subtotal' => 'decimal:2',
        'platform_fee' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function traveler() { return $this->belongsTo(User::class, 'traveler_id'); }
    public function experience() { return $this->belongsTo(Experience::class); }
    public function payments() { return $this->hasMany(Payment::class); }
}
