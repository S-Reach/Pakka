<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stories', function (Blueprint $table) {

            $table->enum(
                'story_approval_status',
                ['pending', 'approved', 'rejected']
            )->default('pending');

            $table->enum(
                'story_status',
                ['ongoing', 'complete']
            )->default('ongoing');
        });
    }

    public function down(): void
    {
        Schema::table('stories', function (Blueprint $table) {

            $table->dropColumn('story_approval_status');
            $table->dropColumn('story_status');
        });
    }
};