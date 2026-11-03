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
        Schema::create('reservasi_ruang', function (Blueprint $table) {
    $table->id();
    $table->char('kode_reservasi', 10)->unique();
    $table->string('nama_peminjam', 100);
    $table->string('email', 100)->unique();
    $table->unsignedTinyInteger('durasi_jam');
    $table->foreignId('ruang_id')->constrained('ruang')->onUpdate('cascade')->onDelete('restrict');
    $table->timestamps();
});

    // Tambahkan CHECK constraint untuk durasi (1 sampai 8 jam)
    DB::statement('ALTER TABLE reservasi_ruang ADD CONSTRAINT check_durasi CHECK (durasi_jam BETWEEN 1 AND 8)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservasi_ruang');
    }
};
