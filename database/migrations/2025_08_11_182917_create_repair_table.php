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
        Schema::create('repairs', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke backlog (tanpa foreign key constraint)
            $table->unsignedBigInteger('id_backlog')->nullable();

            // Informasi unit
            $table->string('kode_unit'); // diambil dari tabel units

            // Waktu perbaikan
            $table->timestamp('tanggal'); 

            // Data dari backlog
            $table->unsignedInteger('hm')->nullable();
            $table->string('component')->nullable();
            $table->string('evidence_temuan')->nullable(); // foto sebelum perbaikan
            $table->string('pic_daily')->nullable();
            $table->string('gl_pic')->nullable();

            // Evidence perbaikan
            $table->string('evidence_perbaikan')->nullable();

            // PIC perbaikan
            $table->string('pic')->nullable();

            // Status perbaikan
            $table->enum('status', ['open', 'closed'])->default('open');

            // Nama pembuat (disimpan langsung, tanpa foreign key)
            $table->string('nama_pembuat');

            // Deskripsi tambahan
            $table->text('deskripsi')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Rollback migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('repairs');
    }
};
