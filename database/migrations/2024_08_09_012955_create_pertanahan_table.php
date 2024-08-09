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
        Schema::create('pertanahan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penduduk_id')->references('id')->on('penduduk')->onDelete('cascade');
            $table->string('nomor_sertifikat');
            $table->enum('tipe_sertifikat', ['surat hak milik', 'letter c']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pertanahan');
    }
};
