<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jobs', function (Blueprint $table) {

            $table->unsignedTinyInteger('job_gfj_status')
                ->default(1)
                ->after('is_active');

            $table->index(
                ['provider', 'job_gfj_status', 'is_active'],
                'jobs_gfj_status_index'
            );
        });
    }

    public function down(): void
    {
        Schema::table('jobs', function (Blueprint $table) {

            $table->dropIndex('jobs_gfj_status_index');

            $table->dropColumn('job_gfj_status');
        });
    }
};