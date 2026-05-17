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
        Schema::create('characters', function (Blueprint $table) {
            $table->id('character_id');
            $table->foreignId('media_id')->constrained('media', 'media_id')->onDelete('cascade');
            $table->foreignId('actor_id')->constrained('actors', 'actor_id')->onDelete('cascade');
            $table->string('nama_karakter'); // Contoh: "Special Week"
            $table->enum('peran', ['Utama', 'Pendukung'])->default('Utama');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('characters');
    }
};
