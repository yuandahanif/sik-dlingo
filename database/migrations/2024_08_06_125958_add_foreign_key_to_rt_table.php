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
            $table->foreignId('ketua_id')->references("id")->on("penduduk")->onDelete('cascade');
            $table->foreignId('dukuh_id')->references('id')->on('dukuh')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rt', function (Blueprint $table) {
            $table->dropForeign(['ketua_id']);
            $table->dropColumn('ketua_id');
            $table->dropForeign(['dukuh_id']);
            $table->dropColumn('dukuh_id');
        });
    }
};
