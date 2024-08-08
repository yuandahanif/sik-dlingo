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
        Schema::create('penduduk', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('nik')->unique();
            $table->foreignId('rt_id')->references('id')->on('rt')->onDelete('cascade');
            $table->enum('jenis_kelamin',['perempuan','laki-laki']);
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->enum('agama',['islam','katolik','protestan','konghucu','buddha','hindu']);
            $table->longtext('alamat');
            $table->enum('status_pernikahan',['kawin','belum kawin','cerai', 'cerai mati']);
            $table->string('pekerjaan');
            $table->enum('status', ['hidup', 'meninggal', 'pindah']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penduduk');
    }
};
