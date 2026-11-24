<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('title', 200);
            $table->string('category', 60);
            $table->unsignedSmallInteger('year')->nullable();
            $table->text('description')->nullable();
            $table->string('file_path');
            $table->string('file_ext', 10);
            $table->unsignedBigInteger('file_size')->default(0);
            $table->unsignedInteger('downloads')->default(0);
            $table->boolean('is_published')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['is_published', 'category']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
