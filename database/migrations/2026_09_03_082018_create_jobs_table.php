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

            // CareerJet source
            $table->string('source', 30)->default('careerjet');
            $table->text('source_url');

            // Job details
            $table->string('title');
            $table->string('company')->nullable();
            $table->longText('description')->nullable();

            // Location
            $table->string('location', 500)->nullable();
            $table->string('city', 150)->nullable();
            $table->string('state', 150)->nullable();
            $table->string('country', 100)->nullable();

            // Salary
            $table->string('salary')->nullable();
            $table->string('salary_currency_code', 10)->nullable();
            $table->decimal('salary_min', 15, 2)->nullable();
            $table->decimal('salary_max', 15, 2)->nullable();
            $table->char('salary_type', 1)->nullable();

            // Job date
            $table->dateTime('posted_at')->nullable();

            // Status
            $table->boolean('is_active')->default(true);

            $table->timestamps();

            // Indexes
            $table->index('title');
            $table->index('source');
            $table->index('company');
            $table->index('city');
            $table->index('state');
            $table->index('country');
            $table->index('posted_at');
            $table->index('is_active');

            // Prevent duplicate jobs from the same source
            $table->unique(['source', 'source_url']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jobs');
    }
};