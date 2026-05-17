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
        Schema::create('media_characters', function (Blueprint $table) {
        $table->id();
        $table->foreignId('media_id')->constrained('media', 'media_id')->onDelete('cascade');
        // SEKARANG MENGARAH KE 'characters' BUKAN 'character_list'
        $table->foreignId('character_id')->constrained('characters', 'character_id')->onDelete('cascade');
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media_characters');
    }
};
