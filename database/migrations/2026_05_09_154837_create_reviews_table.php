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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id('review_id'); 
            $table->foreignId('user_id')->constrained('users', 'user_id')->onDelete('cascade'); 
            $table->foreignId('media_id')->constrained('media', 'media_id')->onDelete('cascade'); 
            $table->decimal('rating', 3, 1); 
            $table->text('komentar')->nullable(); 
            $table->timestamp('waktu_dibuat')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
