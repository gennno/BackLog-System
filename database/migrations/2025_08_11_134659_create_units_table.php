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
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->string('kode_unit')->unique(); // contoh: EX200-01
            $table->string('nama_unit'); // contoh: Excavator Hitachi EX200
            $table->string('foto')->nullable(); // path foto unit
            $table->enum('status', ['aktif', 'off'])->default('aktif'); // status unit
            $table->string('baterai', 10)->nullable(); // contoh: "80%" atau "Low"
            $table->timestamps();
        });
    }

    /**
     * Rollback migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('units');
    }
};
