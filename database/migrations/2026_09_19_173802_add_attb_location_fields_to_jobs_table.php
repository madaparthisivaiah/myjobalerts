<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jobs', function (Blueprint $table) {
        $table->string('city', 100)
            ->nullable()
            ->index()
            ->after('location');

        $table->string('state', 100)
            ->nullable()
            ->index()
            ->after('city');
        });
    }

    public function down(): void
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->dropColumn([           
                'city',
                'state',
             
            ]);
        });
    }
};