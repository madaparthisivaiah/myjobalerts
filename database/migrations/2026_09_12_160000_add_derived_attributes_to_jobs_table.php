<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jobs', function (Blueprint $table) {

            /*
            |--------------------------------------------------------------------------
            | Remote flag
            |--------------------------------------------------------------------------
            |
            | WhatJobs does not send a structured remote/telecommute field.
            |
            | This is derived at sync time from the job title and snippet
            | (e.g. "Fully Remote", labeled "Location: Remote" in snippet).
            |
            */

            $table->boolean('is_remote')
                ->default(false)
                ->after('location');


            /*
            |--------------------------------------------------------------------------
            | Employment type (normalized)
            |--------------------------------------------------------------------------
            |
            | WhatJobs' own job_type column is often blank.
            |
            | This is derived at sync time from a labeled "Type:" field in
            | the snippet, then mapped to schema.org's employmentType enum
            | (FULL_TIME, PART_TIME, CONTRACTOR, TEMPORARY, INTERN,
            | VOLUNTEER, PER_DIEM, OTHER).
            |
            */

            $table->string('employment_type', 20)
                ->nullable()
                ->after('job_type');


            /*
            |--------------------------------------------------------------------------
            | Salary range (normalized)
            |--------------------------------------------------------------------------
            |
            | WhatJobs' own salary column is frequently a placeholder
            | (e.g. "0.000000 - 0.000000").
            |
            | These are derived at sync time from a labeled compensation
            | field in the snippet ("Compensation:", "Salary:", "Pay:", etc).
            |
            | Null when no usable numeric range could be extracted —
            | never store a guessed or zero value here.
            |
            */

            $table->decimal('salary_min', 12, 2)
                ->nullable()
                ->after('salary');

            $table->decimal('salary_max', 12, 2)
                ->nullable()
                ->after('salary_min');

            $table->string('salary_currency', 3)
                ->nullable()
                ->after('salary_max');

            $table->string('salary_unit', 10)
                ->nullable()
                ->after('salary_currency');


            /*
            |--------------------------------------------------------------------------
            | Indexes
            |--------------------------------------------------------------------------
            |
            | is_remote is useful for a "Remote jobs" filter on the site,
            | not just for JobPosting schema output.
            |
            */

            $table->index('is_remote');
        });
    }

    public function down(): void
    {
        Schema::table('jobs', function (Blueprint $table) {

            $table->dropIndex(['is_remote']);

            $table->dropColumn([
                'is_remote',
                'employment_type',
                'salary_min',
                'salary_max',
                'salary_currency',
                'salary_unit',
            ]);
        });
    }
};