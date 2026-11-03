<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Ubah Reservasi</title>
</head>
<body>
    <h1>Ubah Reservasi Ruang</h1>

    @if(session('gagal')) <p>{{ session('gagal') }}</p> @endif

    @if ($errors->any())
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form action="{{ route('reservasi.update', $reservasi['id']) }}" method="POST">
        @csrf
        @method('PUT')
        <p>Kode Reservasi:<br><input type="text" name="kode_reservasi" value="{{ old('kode_reservasi', $reservasi['kode_reservasi']) }}"></p>
        <p>Nama Peminjam:<br><input type="text" name="nama_peminjam" value="{{ old('nama_peminjam', $reservasi['nama_peminjam']) }}"></p>
        <p>Email:<br><input type="email" name="email" value="{{ old('email', $reservasi['email']) }}"></p>
        <p>Durasi (1-8 Jam):<br><input type="number" name="durasi_jam" value="{{ old('durasi_jam', $reservasi['durasi_jam']) }}"></p>
        <p>Ruang:<br>
            <select name="ruang_id">
                @foreach($daftarRuang as $rg)
                    <option value="{{ $rg['id'] }}" @selected(old('ruang_id', $reservasi['ruang_id']) == $rg['id'])>{{ $rg['nama_ruang'] }}</option>
                @endforeach
            </select>
        </p>
        <button type="submit">Simpan Perubahan</button>
        <a href="{{ route('reservasi.index') }}">Kembali</a>
    </form>
</body>
</html>