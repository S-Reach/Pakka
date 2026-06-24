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
        Schema::table('mylibrary', function (Blueprint $table) {
            $table->unsignedBigInteger('last_chapter_id')->nullable()->after('story_id');

            $table->foreign('last_chapter_id')
                ->references('id')
                ->on('chapters')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mylibrary', function (Blueprint $table) {
            $table->dropForeign(['last_chapter_id']);
            $table->dropColumn('last_chapter_id');
        });
    }
};
