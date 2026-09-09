<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    protected $fillable = [
        'provider',
        'provider_job_id',

        'title',
        'company',
        'location',
        'postcode',
        'job_type',
        'salary',

        'snippet',
        'logo',
        'job_url',

        'published_at',
        'age_days',

        'last_seen_at',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',

            'last_seen_at' => 'datetime',

            'age_days' => 'integer',

            'is_active' => 'boolean',
            
        ];
    }
}