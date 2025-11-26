<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserDetail extends Model
{
    protected $fillable = [
        'user_id',
        'languages',
        'currency',
        'id_upload',
        'business_certificate',
        'license',
        'description',
        'cultural_experience',
        'upload_photos',
        'id_upload_status',
        'business_certificate_status',
        'license_status',
        'overall_status',
    ];

    protected $casts = [
        'upload_photos' => 'array',
        'languages' => 'string',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
