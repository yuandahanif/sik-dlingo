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
            $table->uuid('id')->nullable()->default(null);
            $table->string('image');
            $table->string('no_kk', 16);
            $table->unsignedBigInteger('rt_id');
            $table->foreign('rt_id')->references('id')->on('rt')->onDelete('cascade');
            $table->unsignedBigInteger('rw_id');
            $table->foreign('rw_id')->references('id')->on('rw')->onDelete('cascade');
            $table->enum('status_ekonomi',['mampu','tidak mampu']);
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
