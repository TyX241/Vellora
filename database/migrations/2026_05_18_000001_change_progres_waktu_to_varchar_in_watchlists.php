<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('watchlists', function (Blueprint $table) {
            $table->string('progres_waktu', 8)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('watchlists', function (Blueprint $table) {
            $table->time('progres_waktu')->nullable()->change();
        });
    }
};
