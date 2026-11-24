<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Hitungan kunjungan per hari per halaman. Tidak menyimpan IP atau data pribadi.
        Schema::create('page_views', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('path', 190);
            $table->unsignedInteger('views')->default(0);

            $table->unique(['date', 'path']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_views');
    }
};
