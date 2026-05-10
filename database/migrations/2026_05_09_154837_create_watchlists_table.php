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
        Schema::create('watchlists', function (Blueprint $table) {
            $table->id('watchlist_id'); 
            $table->foreignId('user_id')->constrained('users', 'user_id')->onDelete('cascade'); 
            $table->foreignId('media_id')->constrained('media', 'media_id')->onDelete('cascade'); 
            $table->enum('status', ['Plan to Watch', 'Watching', 'Completed', 'Dropped']); 
            $table->integer('progres_episode')->nullable(); 
            $table->time('progres_waktu')->nullable(); 
            $table->timestamp('waktu_diubah')->useCurrent()->useCurrentOnUpdate(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('watchlists');
    }
};
