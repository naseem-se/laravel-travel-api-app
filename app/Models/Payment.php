<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = ['booking_id','payer_id','provider','provider_payment_id','amount','currency','status','meta'];
    protected $casts = ['meta'=>'array','amount'=>'decimal:2'];

    public function booking(){ return $this->belongsTo(Booking::class); }
    public function payer(){ return $this->belongsTo(User::class,'payer_id'); }
}
