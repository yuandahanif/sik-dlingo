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
        Schema::create('pohon_keluarga', function (Blueprint $table) {
            $table->id();
            // $table->foreignId('kk_id')->references('id')->on('kartu_keluarga')->onDelete('no action');
            $table->foreignId('parent_id')->references('id')->on('penduduk')->onDelete('cascade');
            $table->foreignId('child_id')->references('id')->on('penduduk')->onDelete('cascade');
            $table->enum('hubungan', ['ayah', 'ibu']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pohon_keluarga');
    }
};
