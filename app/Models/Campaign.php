<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    protected $fillable = [
        'title',
        'body',
        'tokens',
        'status',
        'location',
        'scheduled_at',
        'topic',
    ];

    protected $casts = [
        'tokens' => 'array',
        'scheduled_at' => 'datetime',
    ];
}
