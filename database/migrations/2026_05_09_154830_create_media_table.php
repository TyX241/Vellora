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
        Schema::create('media', function (Blueprint $table) {
            $table->id('media_id'); 
            $table->string('judul'); 
            $table->enum('format_tayangan', ['Film', 'Series']); 
            $table->string('negara_asal')->nullable();
            $table->boolean('is_animation')->default(false); 
            $table->text('deskripsi')->nullable(); 
            $table->string('poster_url')->nullable(); 
            $table->date('tanggal_rilis')->nullable(); 
            $table->enum('status_tayang', ['Ongoing', 'Completed']); 
            $table->integer('total_episode')->nullable(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
