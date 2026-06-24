<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('chapters', function (Blueprint $table) {

            $table->boolean('is_premium')
                ->default(false);

            $table->decimal('price', 8, 2)
                ->default(0);

        });
    }

    public function down(): void
    {
        Schema::table('chapters', function (Blueprint $table) {

            $table->dropColumn([
                'is_premium',
                'price'
            ]);

        });
    }
};