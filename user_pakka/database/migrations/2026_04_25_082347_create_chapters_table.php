<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('chapters', function (Blueprint $table) {
            $table->id();

            $table->foreignId('story_id')->constrained()->onDelete('cascade');

            $table->integer('chapter_number')->nullable(); // FIXED
            $table->string('title')->nullable();
            $table->longText('content')->nullable();

            $table->string('story_progress')->default('draft');
            $table->integer('views')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chapters');
    }
};
