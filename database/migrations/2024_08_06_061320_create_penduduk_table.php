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
            $table->uuid('id')->nullable();
            $table->string('nama');
            $table->string('nik')->unique();
            $table->unsignedBigInteger('rw_id');
            $table->foreign('rw_id')->references('id')->on('rw')->onDelete('cascade');
            $table->unsignedBigInteger('rt_id');
            $table->foreign('rt_id')->references('id')->on('rt')->onDelete('cascade');
            $table->enum('gender',['Perempuan','Laki-laki']);
            $table->string('tmp_lahir');
            $table->date('tgl_lahir');
            $table->enum('agama',['islam','katolik','protestan','konghucu','buddha','hindu']);
            $table->longtext('alamat');
            $table->enum('status_pernikahan',['kawin','belum kawin','cerai']);
            $table->enum('status_keluarga',['Kepala Rumah Tangga','Isteri','Anak','Lainnya']);
            $table->string('pekerjaan');
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
