<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    protected $fillable = [
        'provider',
        'provider_job_id',

        'title',
        'slug',

        'company',
        'location',
        'is_remote',
        'postcode',
        'job_type',
        'employment_type',
        'salary',
        'salary_min',
        'salary_max',
        'salary_currency',
        'salary_unit',

        'snippet',
        'logo',
        'job_url',

        'published_at',
        'age_days',

        'last_seen_at',
        'is_active',

        'job_gfj_status',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',

            'last_seen_at' => 'datetime',

            'age_days' => 'integer',

            'is_active' => 'boolean',
            'is_remote' => 'boolean',

            'salary_min' => 'decimal:2',
            'salary_max' => 'decimal:2',

            'job_gfj_status' => 'integer',
        ];
    }
}