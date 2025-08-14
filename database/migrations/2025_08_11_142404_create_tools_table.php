<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     */
    public function up(): void
    {
        Schema::create('tools', function (Blueprint $table) {
            $table->id();
            $table->string('kode_tool')->unique(); // contoh: TOOL-001
            $table->string('nama_tool'); // contoh: Kunci Inggris
            $table->string('foto')->nullable(); // path foto alat
            $table->text('deskripsi')->nullable(); // contoh: Berfungsi dengan baik
            $table->enum('status', ['baik', 'rusak'])->default('baik'); // status alat
            $table->timestamps();
        });
    }

    /**
     * Balikkan migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('tools');
    }
};
