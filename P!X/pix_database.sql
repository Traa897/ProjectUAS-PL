-- ============================================
-- DATABASE UPDATE P!X BIOSKOP SYSTEM
-- Sesuai ERD: Tanpa Aktor, dengan User & Admin
-- ============================================

USE pix_database;

-- 1. DROP tabel yang tidak dipakai
DROP TABLE IF EXISTS Film_Aktor;
DROP TABLE IF EXISTS Aktor;

-- 2. Buat tabel USER
CREATE TABLE IF NOT EXISTS User (
    id_user INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    nama_lengkap VARCHAR(100) NOT NULL,
    no_telpon VARCHAR(20),
    tanggal_lahir DATE,
    alamat TEXT,
    tanggal_daftar TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status_akun ENUM('aktif', 'nonaktif') DEFAULT 'aktif'
);

-- 3. Buat tabel ADMIN
CREATE TABLE IF NOT EXISTS Admin (
    id_admin INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    nama_lengkap VARCHAR(100),
    role ENUM('super_admin', 'operator', 'kasir') DEFAULT 'operator',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 4. Update tabel JADWAL_TAYANG - tambah kapasitas
ALTER TABLE Jadwal_Tayang 
ADD COLUMN IF NOT EXISTS kapasitas_tersedia INT DEFAULT 100 AFTER harga_tiket,
ADD COLUMN IF NOT EXISTS kapasitas_total INT DEFAULT 100 AFTER kapasitas_tersedia;

-- Update data existing
UPDATE Jadwal_Tayang 
SET kapasitas_tersedia = 100, kapasitas_total = 100 
WHERE kapasitas_tersedia IS NULL;

-- 5. Buat tabel TRANSAKSI
CREATE TABLE IF NOT EXISTS Transaksi (
    id_transaksi INT PRIMARY KEY AUTO_INCREMENT,
    id_user INT NOT NULL,
    id_admin INT,
    kode_booking VARCHAR(50) UNIQUE NOT NULL,
    tanggal_transaksi DATETIME DEFAULT CURRENT_TIMESTAMP,
    jumlah_tiket INT NOT NULL,
    total_harga DECIMAL(10,2) NOT NULL,
    metode_pembayaran ENUM('transfer', 'e-wallet', 'kartu_kredit', 'tunai') DEFAULT 'tunai',
    status_pembayaran ENUM('pending', 'berhasil', 'gagal', 'expired') DEFAULT 'pending',
    tanggal_pembayaran DATETIME,
    FOREIGN KEY (id_user) REFERENCES User(id_user) ON DELETE CASCADE,
    FOREIGN KEY (id_admin) REFERENCES Admin(id_admin) ON DELETE SET NULL
);

-- 6. Buat tabel DETAIL_TRANSAKSI
CREATE TABLE IF NOT EXISTS Detail_Transaksi (
    id_detail INT PRIMARY KEY AUTO_INCREMENT,
    id_transaksi INT NOT NULL,
    id_jadwal_tayang INT NOT NULL,
    nomor_kursi VARCHAR(10) NOT NULL,
    harga_tiket DECIMAL(10,2) NOT NULL,
    jenis_tiket ENUM('reguler', 'vip') DEFAULT 'reguler',
    FOREIGN KEY (id_transaksi) REFERENCES Transaksi(id_transaksi) ON DELETE CASCADE,
    FOREIGN KEY (id_jadwal_tayang) REFERENCES Jadwal_Tayang(id_tayang) ON DELETE CASCADE
);

-- ============================================
-- INDEX untuk performa
-- ============================================

CREATE INDEX idx_user_email ON User(email);
CREATE INDEX idx_admin_username ON Admin(username);
CREATE INDEX idx_transaksi_user ON Transaksi(id_user);
CREATE INDEX idx_transaksi_kode ON Transaksi(kode_booking);
CREATE INDEX idx_detail_transaksi ON Detail_Transaksi(id_transaksi);
CREATE INDEX idx_detail_jadwal ON Detail_Transaksi(id_jadwal_tayang);

-- ============================================
-- INSERT DATA SAMPLE
-- ============================================

-- Insert Admin Default
INSERT INTO Admin (username, password, nama_lengkap, role) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Super Admin', 'super_admin'),
('operator1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Operator Bioskop', 'operator');
-- Password default: password

-- Insert User Sample
INSERT INTO User (username, email, password, nama_lengkap, no_telpon, tanggal_lahir, alamat) VALUES
('user1', 'user1@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Budi Santoso', '081234567890', '1995-05-15', 'Balikpapan'),
('user2', 'user2@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Siti Nurhaliza', '081298765432', '1998-08-20', 'Samarinda');
-- Password default: password

-- Insert Transaksi Sample
INSERT INTO Transaksi (id_user, id_admin, kode_booking, jumlah_tiket, total_harga, metode_pembayaran, status_pembayaran) VALUES
(1, 1, 'BKG20251124001', 2, 100000, 'transfer', 'berhasil'),
(2, 1, 'BKG20251124002', 3, 150000, 'e-wallet', 'berhasil');

-- Insert Detail Transaksi Sample (dengan kursi random)
INSERT INTO Detail_Transaksi (id_transaksi, id_jadwal_tayang, nomor_kursi, harga_tiket, jenis_tiket) VALUES
(1, 1, 'A5', 50000, 'reguler'),
(1, 1, 'A6', 50000, 'reguler'),
(2, 2, 'B12', 50000, 'reguler'),
(2, 2, 'B13', 50000, 'reguler'),
(2, 2, 'B14', 50000, 'reguler');

-- Update kapasitas jadwal setelah transaksi
UPDATE Jadwal_Tayang SET kapasitas_tersedia = kapasitas_tersedia - 2 WHERE id_tayang = 1;
UPDATE Jadwal_Tayang SET kapasitas_tersedia = kapasitas_tersedia - 3 WHERE id_tayang = 2;

-- ============================================
-- QUERY TESTING
-- ============================================

-- Lihat semua transaksi dengan detail user
SELECT 
    t.kode_booking,
    u.nama_lengkap AS user,
    t.jumlah_tiket,
    t.total_harga,
    t.status_pembayaran,
    t.tanggal_transaksi
FROM Transaksi t
JOIN User u ON t.id_user = u.id_user
ORDER BY t.tanggal_transaksi DESC;

-- Lihat detail tiket per transaksi
SELECT 
    t.kode_booking,
    dt.nomor_kursi,
    f.judul_film,
    b.nama_bioskop,
    jt.tanggal_tayang,
    jt.jam_mulai
FROM Detail_Transaksi dt
JOIN Transaksi t ON dt.id_transaksi = t.id_transaksi
JOIN Jadwal_Tayang jt ON dt.id_jadwal_tayang = jt.id_tayang
JOIN Film f ON jt.id_film = f.id_film
JOIN Bioskop b ON jt.id_bioskop = b.id_bioskop
ORDER BY t.kode_booking;

-- Lihat kursi yang sudah terpesan untuk jadwal tertentu
SELECT 
    nomor_kursi,
    jenis_tiket
FROM Detail_Transaksi
WHERE id_jadwal_tayang = 1
ORDER BY nomor_kursi;

-- ============================================
-- STORED PROCEDURE untuk Generate Kursi Random
-- ============================================

DELIMITER $$

CREATE PROCEDURE generate_kursi_random(
    IN p_id_jadwal INT,
    OUT p_nomor_kursi VARCHAR(10)
)
BEGIN
    DECLARE v_baris CHAR(1);
    DECLARE v_nomor INT;
    DECLARE v_kursi VARCHAR(10);
    DECLARE v_exists INT;
    
    -- Array baris: A-J
    DECLARE v_baris_array VARCHAR(10) DEFAULT 'ABCDEFGHIJ';
    
    SET v_exists = 1;
    
    WHILE v_exists = 1 DO
        -- Random baris (A-J)
        SET v_baris = SUBSTRING(v_baris_array, FLOOR(1 + RAND() * 10), 1);
        
        -- Random nomor (1-10)
        SET v_nomor = FLOOR(1 + RAND() * 10);
        
        -- Gabungkan
        SET v_kursi = CONCAT(v_baris, v_nomor);
        
        -- Cek apakah kursi sudah terpakai
        SELECT COUNT(*) INTO v_exists
        FROM Detail_Transaksi dt
        JOIN Transaksi t ON dt.id_transaksi = t.id_transaksi
        WHERE dt.id_jadwal_tayang = p_id_jadwal 
        AND dt.nomor_kursi = v_kursi
        AND t.status_pembayaran IN ('berhasil', 'pending');
    END WHILE;
    
    SET p_nomor_kursi = v_kursi;
END$$

DELIMITER ;

-- ============================================
-- VIEW untuk kemudahan query
-- ============================================

-- View Transaksi Lengkap
CREATE OR REPLACE VIEW v_transaksi_lengkap AS
SELECT 
    t.id_transaksi,
    t.kode_booking,
    u.username,
    u.nama_lengkap AS nama_user,
    u.email,
    a.nama_lengkap AS nama_admin,
    t.jumlah_tiket,
    t.total_harga,
    t.metode_pembayaran,
    t.status_pembayaran,
    t.tanggal_transaksi,
    t.tanggal_pembayaran
FROM Transaksi t
JOIN User u ON t.id_user = u.id_user
LEFT JOIN Admin a ON t.id_admin = a.id_admin;

-- View Detail Tiket Lengkap
CREATE OR REPLACE VIEW v_detail_tiket AS
SELECT 
    dt.id_detail,
    t.kode_booking,
    u.nama_lengkap AS nama_user,
    f.judul_film,
    b.nama_bioskop,
    b.kota,
    jt.tanggal_tayang,
    jt.jam_mulai,
    jt.jam_selesai,
    dt.nomor_kursi,
    dt.jenis_tiket,
    dt.harga_tiket,
    t.status_pembayaran
FROM Detail_Transaksi dt
JOIN Transaksi t ON dt.id_transaksi = t.id_transaksi
JOIN User u ON t.id_user = u.id_user
JOIN Jadwal_Tayang jt ON dt.id_jadwal_tayang = jt.id_tayang
JOIN Film f ON jt.id_film = f.id_film
JOIN Bioskop b ON jt.id_bioskop = b.id_bioskop;

-- ============================================
-- TRIGGER untuk auto update kapasitas
-- ============================================

DELIMITER $$

CREATE TRIGGER after_detail_insert
AFTER INSERT ON Detail_Transaksi
FOR EACH ROW
BEGIN
    UPDATE Jadwal_Tayang 
    SET kapasitas_tersedia = kapasitas_tersedia - 1
    WHERE id_tayang = NEW.id_jadwal_tayang;
END$$

CREATE TRIGGER after_detail_delete
AFTER DELETE ON Detail_Transaksi
FOR EACH ROW
BEGIN
    UPDATE Jadwal_Tayang 
    SET kapasitas_tersedia = kapasitas_tersedia + 1
    WHERE id_tayang = OLD.id_jadwal_tayang;
END$$

DELIMITER ;

-- ============================================
-- FINISH
-- ============================================

SELECT 'Database P!X berhasil diupdate!' AS status;

---------------------------------------------- INI BATAS ----------------------------------------------
-- Jalankan query ini untuk memperbaiki database
USE pix_database;

-- 1. ALTER tabel Film - tambahkan kolom poster_url
ALTER TABLE Film 
ADD COLUMN poster_url VARCHAR(500) DEFAULT 'https://via.placeholder.com/300x450' AFTER sipnosis;

-- 2. Perbaiki tabel Aktor - hapus id_film (ini salah, seharusnya many-to-many)
ALTER TABLE Aktor 
DROP FOREIGN KEY IF EXISTS aktor_ibfk_1;

ALTER TABLE Aktor 
DROP COLUMN IF EXISTS id_film;

SELECT * FROM film_aktor;

-- 3. Buat tabel junction Film_Aktor untuk relasi many-to-many
CREATE TABLE IF NOT EXISTS Film_Aktor (
    id INT PRIMARY KEY AUTO_INCREMENT,
    id_film INT NOT NULL,
    id_aktor INT NOT NULL,
    peran VARCHAR(100) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_film) REFERENCES Film(id_film) ON DELETE CASCADE,
    FOREIGN KEY (id_aktor) REFERENCES Aktor(id_aktor) ON DELETE CASCADE,
    UNIQUE KEY unique_film_aktor (id_film, id_aktor)
);

-- 4. Tambahkan index untuk performa
CREATE INDEX idx_film_aktor_film ON Film_Aktor(id_film);
CREATE INDEX idx_film_aktor_aktor ON Film_Aktor(id_aktor);

-- 5. Jika ada data lama di Aktor yang punya id_film, migrate ke Film_Aktor
-- (Skip ini jika database masih kosong)

-- 6. Update data sample jika perlu
UPDATE Film 
SET poster_url = 'https://via.placeholder.com/300x450' 
WHERE poster_url IS NULL OR poster_url = '';


-- 1. TABEL GENRE
CREATE TABLE Genre (
    id_genre INT PRIMARY KEY AUTO_INCREMENT,
    nama_genre VARCHAR(50) NOT NULL,
    deskripsi TEXT
);

-- 2. TABEL FILM
CREATE TABLE Film (
    id_film INT PRIMARY KEY AUTO_INCREMENT,
    judul_film VARCHAR(200) NOT NULL,
    tahun_rilis YEAR NOT NULL,
    durasi_menit INT NOT NULL,
    sipnosis TEXT,
    rating DECIMAL(3,1) CHECK (rating >= 0 AND rating <= 10),
    id_genre INT,
    FOREIGN KEY (id_genre) REFERENCES Genre(id_genre) ON DELETE SET NULL
);

-- 3. TABEL AKTOR
CREATE TABLE Aktor (
    id_aktor INT PRIMARY KEY AUTO_INCREMENT,
    nama_aktor VARCHAR(100) NOT NULL,
    tanggal_lahir DATE,
    negara_asal VARCHAR(50),
    id_film INT,
    FOREIGN KEY (id_film) REFERENCES Film(id_film) ON DELETE CASCADE
);

-- 4. TABEL BIOSKOP
CREATE TABLE Bioskop (
    id_bioskop INT PRIMARY KEY AUTO_INCREMENT,
    nama_bioskop VARCHAR(100) NOT NULL,
    kota VARCHAR(50) NOT NULL,
    alamat_bioskop TEXT NOT NULL,
    jumlah_studio INT NOT NULL
);

-- 5. TABEL JADWAL_TAYANG
CREATE TABLE Jadwal_Tayang (
    id_tayang INT PRIMARY KEY AUTO_INCREMENT,
    id_film INT NOT NULL,
    id_bioskop INT NOT NULL,
    nama_tayang VARCHAR(100),
    tanggal_tayang DATE NOT NULL,
    jam_mulai TIME NOT NULL,
    jam_selesai TIME NOT NULL,
    harga_tiket DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (id_film) REFERENCES Film(id_film) ON DELETE CASCADE,
    FOREIGN KEY (id_bioskop) REFERENCES Bioskop(id_bioskop) ON DELETE CASCADE
);

-- ============================================
-- INDEX untuk performa query yang lebih baik
-- ============================================

CREATE INDEX idx_film_genre ON Film(id_genre);
CREATE INDEX idx_film_judul ON Film(judul_film);
CREATE INDEX idx_aktor_film ON Aktor(id_film);
CREATE INDEX idx_jadwal_film ON Jadwal_Tayang(id_film);
CREATE INDEX idx_jadwal_bioskop ON Jadwal_Tayang(id_bioskop);
CREATE INDEX idx_jadwal_tanggal ON Jadwal_Tayang(tanggal_tayang);

-- ============================================
-- CONTOH INSERT DATA
-- ============================================

-- Insert Genre
INSERT INTO Genre (nama_genre, deskripsi) VALUES
('Action', 'Film dengan adegan aksi dan pertarungan'),
('Drama', 'Film dengan cerita emosional dan konflik'),
('Comedy', 'Film yang menghibur dan lucu'),
('Horror', 'Film menakutkan dan menegangkan'),
('Sci-Fi', 'Film fiksi ilmiah dan teknologi masa depan');

-- Insert Film
INSERT INTO Film (judul_film, tahun_rilis, durasi_menit, sipnosis, rating, id_genre) VALUES
('Pengabdi Setan 2', 2022, 119, 'Keluarga Rini kembali diteror oleh sosok ibu mereka yang telah meninggal', 7.5, 4),
('Dilan 1990', 2018, 110, 'Kisah cinta remaja antara Dilan dan Milea di tahun 1990', 8.0, 2),
('The Raid', 2011, 101, 'Tim polisi khusus menyerbu gedung yang dikuasai gembong narkoba', 8.5, 1),
('Keluarga Cemara', 2019, 110, 'Keluarga yang harus bangkit dari keterpurukan ekonomi', 7.8, 2),
('Gundala', 2019, 123, 'Seorang pemuda mendapat kekuatan petir dan menjadi superhero', 7.0, 5);

-- Insert Bioskop
INSERT INTO Bioskop (nama_bioskop, kota, alamat_bioskop, jumlah_studio) VALUES
('CGV Balikpapan', 'Balikpapan', 'Balikpapan Plaza Lt.3, Jl. Jend. Sudirman', 6),
('XXI E-Walk', 'Balikpapan', 'E-Walk Supermall Lt.2, Jl. MT. Haryono', 5),
('Cinepolis BPP', 'Balikpapan', 'Balikpapan Pentacity Lt.3', 4),
('CGV Grand Mall', 'Samarinda', 'Grand Mall Samarinda Lt.3', 5),
('XXI Lembuswana', 'Samarinda', 'Lembuswana Mall Lt.2', 4);

-- Insert Aktor
INSERT INTO Aktor (nama_aktor, tanggal_lahir, negara_asal, id_film) VALUES
('Reza Rahadian', '1987-03-05', 'Indonesia', 4),
('Iqbaal Ramadhan', '1999-12-28', 'Indonesia', 2),
('Vanesha Prescilla', '1999-06-19', 'Indonesia', 2),
('Iko Uwais', '1983-02-12', 'Indonesia', 3),
('Tara Basro', '1990-06-11', 'Indonesia', 1);

-- Insert Jadwal_Tayang
INSERT INTO Jadwal_Tayang (id_film, id_bioskop, nama_tayang, tanggal_tayang, jam_mulai, jam_selesai, harga_tiket) VALUES
(1, 1, 'Pengabdi Setan 2 - Premiere', '2024-11-20', '19:00:00', '21:00:00', 50000),
(2, 1, 'Dilan 1990 - Regular', '2024-11-20', '14:30:00', '16:30:00', 35000),
(3, 2, 'The Raid - Action Night', '2024-11-21', '21:00:00', '23:00:00', 45000),
(4, 3, 'Keluarga Cemara - Family Time', '2024-11-22', '10:00:00', '12:00:00', 30000),
(5, 1, 'Gundala - Weekend Special', '2024-11-23', '16:00:00', '18:30:00', 55000);

-- ============================================
-- QUERY CONTOH UNTUK TESTING
-- ============================================

-- Lihat semua film dengan genre
SELECT f.judul_film, f.tahun_rilis, g.nama_genre, f.rating
FROM Film f
JOIN Genre g ON f.id_genre = g.id_genre
ORDER BY f.rating DESC;

-- Lihat jadwal tayang per bioskop
SELECT 
    b.nama_bioskop,
    f.judul_film,
    jt.tanggal_tayang,
    jt.jam_mulai,
    jt.harga_tiket
FROM Jadwal_Tayang jt
JOIN Film f ON jt.id_film = f.id_film
JOIN Bioskop b ON jt.id_bioskop = b.id_bioskop
ORDER BY jt.tanggal_tayang, jt.jam_mulai;

-- Lihat aktor per film
SELECT 
    f.judul_film,
    GROUP_CONCAT(a.nama_aktor SEPARATOR ', ') as daftar_aktor
FROM Film f
JOIN Aktor a ON f.id_film = a.id_film
GROUP BY f.id_film, f.judul_film;

SHOW TABLES FROM pix_database;








-- Cek tabel Admin
SHOW TABLES LIKE 'Admin';

-- Jika tidak ada, buat tabel Admin
CREATE TABLE IF NOT EXISTS Admin (
    id_admin INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    nama_lengkap VARCHAR(100),
    role ENUM('super_admin', 'operator', 'kasir') DEFAULT 'operator',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert admin default (password: password)
INSERT INTO Admin (username, password, nama_lengkap, role) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Super Admin', 'super_admin');

-- Cek tabel User
SELECT * FROM User LIMIT 5;
=======
