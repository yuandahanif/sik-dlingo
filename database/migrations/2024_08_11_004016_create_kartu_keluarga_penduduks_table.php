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
        Schema::create('kartu_keluarga_penduduk', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penduduk_id')->nullable()->references('id')->on('penduduk')->onDelete('cascade');
            $table->foreignId('kartu_keluarga_id')->nullable()->references('id')->on('kartu_keluarga')->onDelete('cascade');
            $table->enum('status_dalam_keluarga',['kepala keluarga', 'suami','istri','anak', 'lainnya'])->default('anak');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kartu_keluarga_penduduk');
    }
};
