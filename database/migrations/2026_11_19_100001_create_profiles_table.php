<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Satu baris untuk profil BPP (type=bpp) dan satu untuk profil Kecamatan (type=kecamatan).
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['bpp', 'kecamatan'])->default('bpp')->unique();
            $table->string('name', 150);
            $table->string('institution_name', 200)->nullable();
            $table->text('history')->nullable();
            $table->text('description')->nullable();
            $table->text('vision')->nullable();
            $table->text('mission')->nullable();
            $table->text('tasks_functions')->nullable();   // tambahan: tugas & fungsi BPP
            $table->text('geography')->nullable();          // tambahan: letak geografis
            $table->text('environment')->nullable();        // tambahan: kondisi lingkungan
            $table->text('society')->nullable();            // tambahan: kondisi masyarakat
            $table->text('agriculture_potential')->nullable();
            $table->text('address')->nullable();
            $table->string('phone', 30)->nullable();
            $table->string('email', 150)->nullable();
            $table->string('logo')->nullable();
            $table->string('cover_image')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
