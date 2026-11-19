<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('village_id')->nullable()->constrained('villages')->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('name', 200);
            $table->enum('category', [
                'kantor_bpp', 'kantor_desa', 'kelompok_tani',
                'lahan_pertanian', 'lokasi_kegiatan', 'lainnya',
            ])->default('lainnya')->index();
            $table->text('description')->nullable();
            $table->text('extra_info')->nullable();          // tambahan: informasi tambahan
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->string('image')->nullable();
            $table->enum('verification_status', ['draft', 'unverified', 'verified'])
                ->default('unverified')->index();
            $table->boolean('is_published')->default(false);
            $table->timestamps();

            $table->index(['latitude', 'longitude']);
        });

        Schema::create('routes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('start_location_id')->nullable()->constrained('locations')->nullOnDelete();
            $table->foreignId('end_location_id')->nullable()->constrained('locations')->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('name', 200);
            $table->text('description')->nullable();
            $table->json('coordinates');                      // [[lat,lng], ...]
            $table->enum('verification_status', ['draft', 'unverified', 'verified'])
                ->default('unverified')->index();
            $table->boolean('is_published')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('routes');
        Schema::dropIfExists('locations');
    }
};
