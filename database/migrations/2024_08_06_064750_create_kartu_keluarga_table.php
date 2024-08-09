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
        Schema::create('kartu_keluarga', function (Blueprint $table) {
            $table->id();
            $table->string('no_kk', 16)->unique();
            $table->string('image')->nullable();
            $table->enum('status_ekonomi',['mampu','tidak mampu'])->default('mampu');
            $table->enum('status_dalam_keluarga',['ayah','ibu','anak'])->default('anak');
            $table->enum('status_pernikahan',['kawin','belum kawin','cerai', 'cerai mati'])->default('belum kawin');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kartu_keluarga');
    }
};
