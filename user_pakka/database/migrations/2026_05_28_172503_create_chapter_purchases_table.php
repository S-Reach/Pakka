<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chapter_purchases', function (Blueprint $table) {

            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->onDelete('cascade');

            $table->foreignId('chapter_id')
                ->constrained()
                ->onDelete('cascade');

            $table->string('transaction_id')
                ->unique();

            $table->decimal('amount', 8, 2);

            $table->string('payment_status')
                ->default('pending');

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chapter_purchases');
    }
};