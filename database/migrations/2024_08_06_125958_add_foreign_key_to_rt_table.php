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
            $table->foreignId('ketua_id')->nullable()->references("id")->on("penduduk")->nullOnDelete();
            $table->foreignId('dukuh_id')->nullable()->references('id')->on('dukuh')->onDelete('cascade')->nullOnDelete();
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
