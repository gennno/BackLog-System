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
        Schema::create('backlog', function (Blueprint $table) {
            $table->id();

            // Tanggal Temuan
            $table->date('tanggal_temuan');

            // ID Inspeksi
            $table->string('id_inspeksi'); // contoh: INSP-2025-001

            // Code Number
            $table->string('code_number')->nullable(); // contoh: CN-001

            // HM (Hour Meter)
            $table->unsignedInteger('hm')->nullable(); // contoh: 12345

            // Component
            $table->string('component')->nullable();

            // Plan Repair
            $table->enum('plan_repair', ['Next PS', 'No Repair'])->nullable();

            // Status Temuan
            $table->enum('status', ['Open BL', 'Close'])->default('Open BL');

            // Condition
            $table->enum('condition', ['Caution', 'Urgent'])->nullable();

            // GL PIC
            $table->string('gl_pic')->nullable();

            // PIC Daily
            $table->string('pic_daily')->nullable();

            // Evidence Foto
            $table->string('evidence')->nullable(); // path foto

            // Deskripsi
            $table->text('deskripsi')->nullable();

            // Spare Part Info
            $table->string('part_number')->nullable();
            $table->string('part_name')->nullable();
            $table->string('no_figure')->nullable();
            $table->unsignedInteger('qty')->nullable();

            // Close Info
            $table->string('close_by')->nullable();

            // Action ID (relasi opsional ke tabel action)
            $table->unsignedBigInteger('id_action')->nullable();

            // User yang membuat backlog
            $table->unsignedBigInteger('made_by'); // relasi ke tabel users (id)

            $table->timestamps();
        });
    }

    /**
     * Rollback migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('backlog');
    }
};
