<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tambahan: data penyuluh pertanian (halaman Tentang BPP).
        Schema::create('extension_workers', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->string('position', 150)->nullable();
            $table->string('working_area', 200)->nullable();
            $table->text('bio')->nullable();
            $table->string('photo')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        // Tambahan: pengaturan website (slogan, media sosial, hero, pusat peta, batas wilayah).
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key', 100)->unique();
            $table->longText('value')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
        Schema::dropIfExists('extension_workers');
    }
};
