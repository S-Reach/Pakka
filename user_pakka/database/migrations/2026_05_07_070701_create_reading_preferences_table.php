<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reading_preferences', function (Blueprint $table) {

            $table->id();

            $table->foreignId('user_id')
                ->unique()
                ->constrained()
                ->onDelete('cascade');

            $table->string('theme')->default('light');

            $table->string('font')->default('sans');

            $table->integer('size')->default(16);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reading_preferences');
    }
};