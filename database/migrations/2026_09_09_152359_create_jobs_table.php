<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jobs', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Provider
            |--------------------------------------------------------------------------
            */

            $table->string('provider', 50);

            $table->string('provider_job_id', 255);


            /*
            |--------------------------------------------------------------------------
            | Job information
            |--------------------------------------------------------------------------
            */

            $table->string('title');

            $table->string('company')->nullable();

            $table->string('location')->nullable();

            $table->string('postcode', 50)->nullable();

            $table->string('job_type', 100)->nullable();

            $table->string('salary')->nullable();


            /*
            |--------------------------------------------------------------------------
            | Job description / snippet
            |--------------------------------------------------------------------------
            */

            $table->longText('snippet')->nullable();


            /*
            |--------------------------------------------------------------------------
            | Company / job logo
            |--------------------------------------------------------------------------
            */

            $table->text('logo')->nullable();


            /*
            |--------------------------------------------------------------------------
            | Original provider URL
            |--------------------------------------------------------------------------
            */

            $table->text('job_url');


            /*
            |--------------------------------------------------------------------------
            | Date information
            |--------------------------------------------------------------------------
            */

            $table->timestamp('published_at')->nullable();

            $table->unsignedInteger('age_days')->nullable();


            /*
            |--------------------------------------------------------------------------
            | Synchronization
            |--------------------------------------------------------------------------
            */

            $table->timestamp('last_seen_at')->nullable();

            $table->boolean('is_active')->default(true);


            /*
            |--------------------------------------------------------------------------
            | Timestamps
            |--------------------------------------------------------------------------
            */

            $table->timestamps();


            /*
            |--------------------------------------------------------------------------
            | Unique provider job
            |--------------------------------------------------------------------------
            |
            | Same provider + same job ID = same job.
            |
            */

            $table->unique(
                ['provider', 'provider_job_id'],
                'jobs_provider_job_unique'
            );


            /*
            |--------------------------------------------------------------------------
            | Indexes
            |--------------------------------------------------------------------------
            */

            $table->index('provider');

            $table->index('is_active');

            $table->index('title');

            $table->index('location');

            $table->index('company');

            $table->index('last_seen_at');

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jobs');
    }
};