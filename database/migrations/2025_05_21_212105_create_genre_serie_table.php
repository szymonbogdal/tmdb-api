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
        Schema::create('genre_serie', function (Blueprint $table) {
            $table->foreignId('genre_id')->constrained()->onDelete('cascade');
            $table->foreignId('serie_id')->constrained()->onDelete('cascade');
            $table->primary(['genre_id', 'serie_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('genre_serie');
    }
};
