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
        Schema::create('story_ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('story_id')->constrained()->onDelete('cascade');
            $table->integer('rating')->unsigned(); // 1 to 5
            $table->timestamps();
            
            $table->unique(['user_id', 'story_id']);// Ensure a user can only rate a story once
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('story_ratings');
    }
};
