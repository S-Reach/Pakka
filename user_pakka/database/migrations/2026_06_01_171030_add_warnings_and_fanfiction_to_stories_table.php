<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('stories', function (Blueprint $table) {

            // Content Warning
            $table->json('warnings')
                  ->nullable()
                  ->after('tags');

            // Content Ownership Verification
            $table->boolean('is_fanfiction')
                  ->default(false)
                  ->after('warnings');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stories', function (Blueprint $table) {

            $table->dropColumn([
                'warnings',
                'is_fanfiction'
            ]);

        });
    }
};