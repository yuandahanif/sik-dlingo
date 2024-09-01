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
        Schema::create('asuransi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_id')->references('id')->on('asuransi_kategori')->onDelete('cascade');
            $table->foreignId('penduduk_id')->references('id')->on('penduduk')->onDelete('cascade');
            $table->longText('keterangan')->nullable();
            $table->string('nomor_asuransi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asuransi');
    }
};
