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
        Schema::table('rt', function (Blueprint $table) {
            $table->foreignId('kepala_id')->nullable()->references("id")->on("penduduk")->nullOnDelete();
            $table->foreignId('dusun_id')->nullable()->references('id')->on('dusun')->onDelete('cascade')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rt', function (Blueprint $table) {
            $table->dropForeign(['kepala_id']);
            $table->dropColumn('kepala_id');
            $table->dropForeign(['dusun_id']);
            $table->dropColumn('dusun_id');
        });
    }
};
