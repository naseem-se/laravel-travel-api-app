<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExperienceTimeSlot extends Model
{
    protected $fillable = ['experience_id', 'start_day', 'end_day', 'start_time', 'end_time'];
}
