<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ReservasiRuang;

class ReservasiController extends Controller
{
    public function index(Request $request) {
        try {
            $kataKunci = trim((string) $request->query('q', ''));
            $ruangId = $request->query('ruang_id', '');

            $pdo = DB::connection()->getPdo();
            // Query dasar Read dengan JOIN ke tabel master ruang
            $sql = "SELECT r.*, rg.nama_ruang FROM reservasi_ruang r 
                    JOIN ruang rg ON r.ruang_id = rg.id WHERE 1=1";
            $params = [];

            // Pencarian memeriksa 3 kolom: kode_reservasi, nama_peminjam, dan email
            if ($kataKunci !== '') {
                $sql .= " AND (r.kode_reservasi LIKE :q1 OR r.nama_peminjam LIKE :q2 OR r.email LIKE :q3)";
                $params['q1'] = "%$kataKunci%";
                $params['q2'] = "%$kataKunci%";
                $params['q3'] = "%$kataKunci%";
            }

            // Filter berdasarkan ruang_id
            if ($ruangId !== '') {
                $sql .= " AND r.ruang_id = :ruang_id";
                $params['ruang_id'] = (int)$ruangId;
            }

            $sql .= " ORDER BY r.kode_reservasi ASC";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            $daftarReservasi = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            // Ambil daftar ruang untuk dropdown filter dan form
            $stmtRuang = $pdo->prepare("SELECT * FROM ruang ORDER BY nama_ruang ASC");
            $stmtRuang->execute();
            $daftarRuang = $stmtRuang->fetchAll(\PDO::FETCH_ASSOC);

            return view('reservasi.index', compact('daftarReservasi', 'daftarRuang', 'kataKunci', 'ruangId'));
        } catch (\Throwable $error) {
            report($error);
            return response("Terjadi kesalahan pada sistem. Periksa koneksi dan query.", 500);
        }
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'kode_reservasi' => ['required', 'size:10', 'alpha_num', 'unique:reservasi_ruang'],
            'nama_peminjam' => ['required', 'min:3', 'max:100'],
            'email' => ['required', 'email', 'max:100', 'unique:reservasi_ruang'],
            'durasi_jam' => ['required', 'integer', 'min:1', 'max:8'],
            'ruang_id' => ['required', 'exists:ruang,id'],
        ]);

        try {
            $pdo = DB::connection()->getPdo();
            $stmt = $pdo->prepare("INSERT INTO reservasi_ruang (kode_reservasi, nama_peminjam, email, durasi_jam, ruang_id, created_at, updated_at) VALUES (:kode, :nama, :email, :durasi, :ruang_id, NOW(), NOW())");
            $stmt->execute([
                'kode' => $validated['kode_reservasi'],
                'nama' => strip_tags($validated['nama_peminjam']),
                'email' => $validated['email'],
                'durasi' => $validated['durasi_jam'],
                'ruang_id' => $validated['ruang_id']
            ]);

            return redirect()->route('reservasi.index')->with('berhasil', 'Reservasi berhasil ditambahkan.');
        } catch (\Throwable $error) {
            report($error);
            return back()->withInput()->with('gagal', 'Data gagal disimpan. Periksa koneksi dan data.');
        }
    }

    public function edit($id) {
        try {
            $pdo = DB::connection()->getPdo();
            $stmt = $pdo->prepare("SELECT * FROM reservasi_ruang WHERE id = :id");
            $stmt->execute(['id' => $id]);
            $reservasi = $stmt->fetch(\PDO::FETCH_ASSOC);

            if (!$reservasi) {
                return redirect()->route('reservasi.index')->with('gagal', 'Data reservasi tidak ditemukan.');
            }

            $stmtRuang = $pdo->prepare("SELECT * FROM ruang ORDER BY nama_ruang ASC");
            $stmtRuang->execute();
            $daftarRuang = $stmtRuang->fetchAll(\PDO::FETCH_ASSOC);

            return view('reservasi.edit', compact('reservasi', 'daftarRuang'));
        } catch (\Throwable $error) {
            report($error);
            return redirect()->route('reservasi.index')->with('gagal', 'Data gagal dibuka untuk diubah.');
        }
    }

    public function update(Request $request, $id) {
        $validated = $request->validate([
            'kode_reservasi' => ['required', 'size:10', 'alpha_num', "unique:reservasi_ruang,kode_reservasi,$id"],
            'nama_peminjam' => ['required', 'min:3', 'max:100'],
            'email' => ['required', 'email', 'max:100', "unique:reservasi_ruang,email,$id"],
            'durasi_jam' => ['required', 'integer', 'min:1', 'max:8'],
            'ruang_id' => ['required', 'exists:ruang,id'],
        ]);

        try {
            $pdo = DB::connection()->getPdo();
            $stmt = $pdo->prepare("UPDATE reservasi_ruang SET kode_reservasi = :kode, nama_peminjam = :nama, email = :email, durasi_jam = :durasi, ruang_id = :ruang_id, updated_at = NOW() WHERE id = :id");
            $stmt->execute([
                'kode' => $validated['kode_reservasi'],
                'nama' => strip_tags($validated['nama_peminjam']),
                'email' => $validated['email'],
                'durasi' => $validated['durasi_jam'],
                'ruang_id' => $validated['ruang_id'],
                'id' => $id
            ]);

            return redirect()->route('reservasi.index')->with('berhasil', 'Reservasi berhasil diperbarui.');
        } catch (\Throwable $error) {
            report($error);
            return back()->withInput()->with('gagal', 'Data gagal diperbarui. Periksa koneksi dan data.');
        }
    }

    public function destroy($id) {
        try {
            $pdo = DB::connection()->getPdo();
            $stmt = $pdo->prepare("DELETE FROM reservasi_ruang WHERE id = :id");
            $stmt->execute(['id' => $id]);

            return redirect()->route('reservasi.index')->with('berhasil', 'Reservasi berhasil dihapus.');
        } catch (\Throwable $error) {
            report($error);
            return redirect()->route('reservasi.index')->with('gagal', 'Data gagal dihapus. Periksa relasi atau koneksi.');
        }
    }
}
