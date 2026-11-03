<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sistem Reservasi Ruang</title>
</head>
<body>
    <h1>Reservasi Ruang Kampus</h1>

    @if(session('berhasil')) <p>{{ session('berhasil') }}</p> @endif
    @if(session('gagal')) <p>{{ session('gagal') }}</p> @endif

    @if ($errors->any())
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <h2>Form Tambah Reservasi</h2>
    <form action="{{ route('reservasi.store') }}" method="POST">
        @csrf
        <p>Kode Reservasi (10 Karakter):<br><input type="text" name="kode_reservasi" value="{{ old('kode_reservasi') }}"></p>
        <p>Nama Peminjam:<br><input type="text" name="nama_peminjam" value="{{ old('nama_peminjam') }}"></p>
        <p>Email:<br><input type="email" name="email" value="{{ old('email') }}"></p>
        <p>Durasi (1-8 Jam):<br><input type="number" name="durasi_jam" value="{{ old('durasi_jam') }}"></p>
        <p>Ruang:<br>
            <select name="ruang_id">
                <option value="">-- Pilih Ruang --</option>
                @foreach($daftarRuang as $rg)
                    <option value="{{ $rg['id'] }}" @selected(old('ruang_id') == $rg['id'])>{{ $rg['nama_ruang'] }}</option>
                @endforeach
            </select>
        </p>
        <button type="submit">Tambah Data</button>
    </form>

    <h2>Pencarian dan Filter</h2>
    <form action="{{ route('reservasi.index') }}" method="GET">
        <p>Kata Kunci (Kode/Nama/Email):<br><input type="text" name="q" value="{{ $kataKunci }}"></p>
        <p>Filter Ruang:<br>
            <select name="ruang_id">
                <option value="">-- Semua Ruang --</option>
                @foreach($daftarRuang as $rg)
                    <option value="{{ $rg['id'] }}" @selected($ruangId == $rg['id'])>{{ $rg['nama_ruang'] }}</option>
                @endforeach
            </select>
        </p>
        <button type="submit">Cari</button>
        <a href="{{ route('reservasi.index') }}">Reset</a>
    </form>

    <h2>Daftar Reservasi</h2>
    <table border="1" cellpadding="6" cellspacing="0">
        <tr>
            <th>Kode</th>
            <th>Nama Peminjam</th>
            <th>Email</th>
            <th>Durasi</th>
            <th>Status Durasi</th>
            <th>Ruang</th>
            <th>Aksi</th>
        </tr>
        @forelse ($daftarReservasi as $item)
            <tr>
                <td>{{ $item['kode_reservasi'] }}</td>
                <td>{{ $item['nama_peminjam'] }}</td>
                <td>{{ $item['email'] }}</td>
                <td>{{ $item['durasi_jam'] }} Jam</td>
                <td>{{ \App\Models\ReservasiRuang::statusDurasi($item['durasi_jam']) }}</td>
                <td>{{ $item['nama_ruang'] }}</td>
                <td>
                    <a href="{{ route('reservasi.edit', $item['id']) }}">Ubah</a>
                    <form action="{{ route('reservasi.destroy', $item['id']) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Hapus data ini?')">Hapus</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7">Data tidak ditemukan.</td>
            </tr>
        @endforelse
    </table>
</body>
</html>