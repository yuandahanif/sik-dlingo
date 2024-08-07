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
        Schema::create('kk_penduduk_inheritance', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('from_id')->references('id')->on('penduduk')->onDelete('cascade');
            $table->foreignUuid('to_id')->references('id')->on('kartu_keluarga')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kk_penduduk_inheritance');
    }
};
