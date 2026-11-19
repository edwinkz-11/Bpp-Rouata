<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('commodities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('village_id')->nullable()->constrained('villages')->nullOnDelete();
            $table->string('name', 150);
            $table->string('category', 100)->index();
            $table->text('description')->nullable();
            $table->string('location_note')->nullable();   // tambahan: lokasi pengembangan (teks bebas)
            $table->text('extra_info')->nullable();         // tambahan: informasi tambahan
            $table->string('image')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('commodities');
    }
};
