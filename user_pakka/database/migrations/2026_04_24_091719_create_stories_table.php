<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stories', function (Blueprint $table) {
            $table->id();

            // IMPORTANT: link story to user
            $table->foreignId('user_id')
                ->constrained()
                ->onDelete('cascade');

            $table->string('title');
            $table->text('synopsis');
            $table->string('language');
            $table->json('genres')->nullable();
            $table->json('tags')->nullable();
            $table->enum('format', ['serialized'])->default('serialized');
            $table->string('cover_image')->nullable();
            $table->boolean('is_premium')->default(false);
            $table->integer('price')->nullable();
            $table->enum('story_progress', ['draft', 'published'])->default('draft');
            $table->integer('views')->default(0);
            $table->integer('likes')->default(0);
            $table->float('rating')->nullable()->default(0);
            $table->integer('ratings_count')->nullable()->default(0);
            $table->integer('earnings')->default(0);
            $table->text('content')->nullable(); // for short stories

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stories');
    }
};