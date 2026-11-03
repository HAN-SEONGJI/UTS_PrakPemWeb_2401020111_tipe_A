-- Buat database sesuai ketentuan NIM (contoh NIM: 2401020111)
CREATE DATABASE IF NOT EXISTS reservasi_ruang_2401020111;
USE reservasi_ruang_2401020111;

-- Tabel Master: ruang
CREATE TABLE ruang (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nama_ruang VARCHAR(100) NOT NULL UNIQUE,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL
);

-- Tabel Utama: reservasi_ruang
CREATE TABLE reservasi_ruang (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    kode_reservasi CHAR(10) NOT NULL UNIQUE,
    nama_peminjam VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    durasi_jam TINYINT UNSIGNED NOT NULL CHECK (durasi_jam BETWEEN 1 AND 8),
    ruang_id BIGINT UNSIGNED NOT NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    CONSTRAINT fk_ruang FOREIGN KEY (ruang_id) REFERENCES ruang(id) 
        ON UPDATE CASCADE 
        ON DELETE RESTRICT
);

-- Masukkan Data Awal Master Ruang
INSERT INTO ruang (nama_ruang, created_at, updated_at) VALUES 
('Ruang Rapat A', NOW(), NOW()),
('Ruang Seminar', NOW(), NOW());

-- Masukkan Data Awal Wajib Reservasi Ruang
INSERT INTO reservasi_ruang (kode_reservasi, nama_peminjam, email, durasi_jam, ruang_id, created_at, updated_at) VALUES 
('RS00000001', 'Andi Saputra', 'andi.r@example.com', 2, 1, NOW(), NOW()),
('RS00000002', 'Siti Rahma', 'siti.r@example.com', 3, 2, NOW(), NOW()),
('RS00000003', 'Budi Pratama', 'budi.r@example.com', 1, 1, NOW(), NOW());