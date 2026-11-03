<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReservasiRuang extends Model
{
    protected $table = 'reservasi_ruang';
    protected $fillable = ['kode_reservasi', 'nama_peminjam', 'email', 'durasi_jam', 'ruang_id'];

    public function ruang() {
        return $this->belongsTo(Ruang::class, 'ruang_id');
    }

    // Fungsi PHP sederhana sesuai ketentuan Tipe A
    public static function statusDurasi($durasi) {
        return $durasi >= 4 ? 'Panjang' : 'Singkat';
    }
}
