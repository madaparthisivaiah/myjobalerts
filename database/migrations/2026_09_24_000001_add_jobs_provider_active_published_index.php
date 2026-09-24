<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Your current indexes cover provider / is_active separately, but
     * every listing query also does ORDER BY published_at DESC. Without
     * published_at in the index, MySQL has to sort the whole matched
     * result set on every request (filesort). This composite index lets
     * it satisfy the WHERE + ORDER BY in one pass.
     */
    public function up(): void
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->index(
                ['provider', 'is_active', 'published_at'],
                'jobs_provider_active_published_index'
            );
        });
    }

    public function down(): void
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->dropIndex('jobs_provider_active_published_index');
        });
    }
};
