<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Withdrawal extends Model
{
    protected $fillable = ['user_id','amount','currency','status','payout_provider','payout_provider_id','admin_note','meta'];
    protected $casts = ['meta'=>'array','amount'=>'decimal:2'];

    public function user(){ return $this->belongsTo(User::class,'user_id'); }
}
