-- ============================================
-- DATABASE P!X BIOSKOP SYSTEM 
-- Versi: 2.0 (Clean & Structured)
-- Tanggal: 26 November 2024
-- ============================================

CREATE DATABASE pix_database CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE pix_database;

-- ============================================
-- TABEL MASTER DATA
-- ============================================

-- 1. Tabel Genre
CREATE TABLE Genre (
    id_genre INT PRIMARY KEY AUTO_INCREMENT,
    nama_genre VARCHAR(50) NOT NULL,
    deskripsi TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- 2. Tabel Film
CREATE TABLE Film (
    id_film INT PRIMARY KEY AUTO_INCREMENT,
    judul_film VARCHAR(200) NOT NULL,
    tahun_rilis YEAR NOT NULL,
    durasi_menit INT NOT NULL,
    sipnosis TEXT,
    rating DECIMAL(3,1) CHECK (rating >= 0 AND rating <= 10),
    poster_url VARCHAR(500) DEFAULT 'https://via.placeholder.com/300x450',
    id_genre INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_genre) REFERENCES Genre(id_genre) ON DELETE SET NULL,
    INDEX idx_film_genre (id_genre),
    INDEX idx_film_judul (judul_film),
    INDEX idx_film_rating (rating)
) ENGINE=InnoDB;

-- 3. Tabel Bioskop
CREATE TABLE Bioskop (
    id_bioskop INT PRIMARY KEY AUTO_INCREMENT,
    nama_bioskop VARCHAR(100) NOT NULL,
    kota VARCHAR(50) NOT NULL,
    alamat_bioskop TEXT NOT NULL,
    jumlah_studio INT NOT NULL DEFAULT 4,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_bioskop_kota (kota)
) ENGINE=InnoDB;

-- 4. Tabel Jadwal Tayang
CREATE TABLE Jadwal_Tayang (
    id_tayang INT PRIMARY KEY AUTO_INCREMENT,
    id_film INT NOT NULL,
    id_bioskop INT NOT NULL,
    nama_tayang VARCHAR(100),
    tanggal_tayang DATE NOT NULL,
    jam_mulai TIME NOT NULL,
    jam_selesai TIME NOT NULL,
    harga_tiket DECIMAL(10,2) NOT NULL,
    kapasitas_total INT DEFAULT 100,
    kapasitas_tersedia INT DEFAULT 100,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_film) REFERENCES Film(id_film) ON DELETE CASCADE,
    FOREIGN KEY (id_bioskop) REFERENCES Bioskop(id_bioskop) ON DELETE CASCADE,
    INDEX idx_jadwal_film (id_film),
    INDEX idx_jadwal_bioskop (id_bioskop),
    INDEX idx_jadwal_tanggal (tanggal_tayang)
) ENGINE=InnoDB;

-- ============================================
-- TABEL USER & ADMIN
-- ============================================

-- 5. Tabel User
CREATE TABLE User (
    id_user INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    nama_lengkap VARCHAR(100) NOT NULL,
    no_telpon VARCHAR(20),
    tanggal_lahir DATE,
    alamat TEXT,
    tanggal_daftar TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status_akun ENUM('aktif', 'nonaktif') DEFAULT 'aktif',
    INDEX idx_user_email (email),
    INDEX idx_user_username (username)
) ENGINE=InnoDB;

-- 6. Tabel Admin
CREATE TABLE Admin (
    id_admin INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    nama_lengkap VARCHAR(100),
    role ENUM('super_admin', 'operator', 'kasir') DEFAULT 'operator',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_admin_username (username)
) ENGINE=InnoDB;

-- ============================================
-- TABEL TRANSAKSI
-- ============================================

-- 7. Tabel Transaksi
CREATE TABLE Transaksi (
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
    FOREIGN KEY (id_admin) REFERENCES Admin(id_admin) ON DELETE SET NULL,
    INDEX idx_transaksi_user (id_user),
    INDEX idx_transaksi_kode (kode_booking),
    INDEX idx_transaksi_status (status_pembayaran),
    INDEX idx_transaksi_tanggal (tanggal_transaksi)
) ENGINE=InnoDB;

-- 8. Tabel Detail Transaksi
CREATE TABLE Detail_Transaksi (
    id_detail INT PRIMARY KEY AUTO_INCREMENT,
    id_transaksi INT NOT NULL,
    id_jadwal_tayang INT NOT NULL,
    nomor_kursi VARCHAR(10) NOT NULL,
    harga_tiket DECIMAL(10,2) NOT NULL,
    jenis_tiket ENUM('reguler', 'vip') DEFAULT 'reguler',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_transaksi) REFERENCES Transaksi(id_transaksi) ON DELETE CASCADE,
    FOREIGN KEY (id_jadwal_tayang) REFERENCES Jadwal_Tayang(id_tayang) ON DELETE CASCADE,
    INDEX idx_detail_transaksi (id_transaksi),
    INDEX idx_detail_jadwal (id_jadwal_tayang),
    UNIQUE KEY unique_kursi_jadwal (id_jadwal_tayang, nomor_kursi)
) ENGINE=InnoDB;

-- ============================================
-- INSERT DATA MASTER
-- ============================================

-- Insert Genre
INSERT INTO Genre (nama_genre, deskripsi) VALUES
('Action', 'Film dengan adegan aksi dan pertarungan'),
('Drama', 'Film dengan cerita emosional dan konflik'),
('Comedy', 'Film yang menghibur dan lucu'),
('Horror', 'Film menakutkan dan menegangkan'),
('Sci-Fi', 'Film fiksi ilmiah dan teknologi masa depan'),
('Romance', 'Film dengan tema percintaan'),
('Thriller', 'Film menegangkan dan penuh misteri'),
('Animation', 'Film animasi untuk semua usia');

-- Insert Film
INSERT INTO Film (judul_film, tahun_rilis, durasi_menit, sipnosis, rating, poster_url, id_genre) VALUES
('Pengabdi Setan 2: Communion', 2022, 119, 'Rini dan keluarganya berusaha bertahan dari teror ibunya yang telah meninggal. Mereka pindah ke apartemen kumuh di Jakarta, namun teror justru semakin mengintensif.', 7.5, 'https://via.placeholder.com/300x450', 4),
('Dilan 1990', 2018, 110, 'Kisah cinta remaja antara Dilan dan Milea di tahun 1990 di Bandung. Dilan adalah seorang siswa SMA yang cerdas dan unik dalam mendekati wanita.', 8.0, 'https://via.placeholder.com/300x450', 6),
('The Raid: Redemption', 2011, 101, 'Tim elit polisi Indonesia menyerbu gedung bertingkat yang dikuasai gembong narkoba kejam. Pertarungan sengit dimulai di setiap lantai.', 8.5, 'https://via.placeholder.com/300x450', 1),
('Keluarga Cemara', 2019, 110, 'Keluarga yang dulunya kaya raya harus bangkit dari keterpurukan ekonomi. Mereka belajar arti kebahagiaan yang sesungguhnya.', 7.8, 'https://via.placeholder.com/300x450', 2),
('Gundala', 2019, 123, 'Saka, seorang pemuda biasa yang mendapat kekuatan petir setelah tersambar. Ia menjadi superhero Indonesia pertama yang melawan kejahatan.', 7.0, 'https://via.placeholder.com/300x450', 5),
('Laskar Pelangi', 2008, 125, 'Perjuangan 10 anak Belitung untuk mendapatkan pendidikan di sekolah Muhammadiyah yang terancam ditutup.', 8.2, 'https://via.placeholder.com/300x450', 2),
('Ada Apa Dengan Cinta?', 2002, 112, 'Kisah cinta remaja antara Cinta dan Rangga yang berbeda latar belakang dan kepribadian.', 7.9, 'https://via.placeholder.com/300x450', 6),
('Warkop DKI Reborn: Jangkrik Boss', 2016, 100, 'Tiga detektif kocak yang selalu membuat kekacauan dalam menjalankan misi mereka.', 6.5, 'https://via.placeholder.com/300x450', 3);

-- Insert Bioskop
INSERT INTO Bioskop (nama_bioskop, kota, alamat_bioskop, jumlah_studio) VALUES
('CGV Balikpapan Plaza', 'Balikpapan', 'Balikpapan Plaza Lt.3, Jl. Jend. Sudirman No.1', 6),
('XXI E-Walk Balikpapan', 'Balikpapan', 'E-Walk Supermall Lt.2, Jl. MT. Haryono', 5),
('Cinepolis Balikpapan Pentacity', 'Balikpapan', 'Balikpapan Pentacity Lt.3, Jl. Soekarno Hatta', 4),
('CGV Grand Mall Samarinda', 'Samarinda', 'Grand Mall Samarinda Lt.3, Jl. Panglima Batur', 5),
('XXI Lembuswana Samarinda', 'Samarinda', 'Lembuswana Mall Lt.2, Jl. Basuki Rahmat', 4),
('Cinepolis Big Mall Samarinda', 'Samarinda', 'Big Mall Samarinda Lt.2, Jl. Pramuka', 5);

-- Insert Jadwal Tayang
INSERT INTO Jadwal_Tayang (id_film, id_bioskop, nama_tayang, tanggal_tayang, jam_mulai, jam_selesai, harga_tiket) VALUES
-- Pengabdi Setan 2
(1, 1, 'Premiere Night', '2024-12-01', '19:00:00', '21:00:00', 50000),
(1, 2, 'Weekend Special', '2024-12-02', '20:30:00', '22:30:00', 45000),
(1, 4, 'Horror Night', '2024-12-01', '21:00:00', '23:00:00', 50000),
-- Dilan 1990
(2, 1, 'Regular Show', '2024-12-01', '14:30:00', '16:30:00', 35000),
(2, 3, 'Matinee Show', '2024-12-02', '13:00:00', '15:00:00', 30000),
(2, 5, 'Romance Night', '2024-12-01', '19:30:00', '21:30:00', 40000),
-- The Raid
(3, 2, 'Action Night', '2024-12-03', '21:00:00', '23:00:00', 45000),
(3, 4, 'Midnight Show', '2024-12-02', '23:30:00', '01:30:00', 40000),
-- Keluarga Cemara
(4, 3, 'Family Time', '2024-12-02', '10:00:00', '12:00:00', 30000),
(4, 5, 'Weekend Family', '2024-12-03', '11:00:00', '13:00:00', 35000),
-- Gundala
(5, 1, 'Superhero Special', '2024-12-03', '16:00:00', '18:30:00', 55000),
(5, 6, 'Weekend Show', '2024-12-02', '17:00:00', '19:30:00', 50000);

-- ============================================
-- INSERT USER & ADMIN
-- ============================================

-- Insert Admin (Password: password)
INSERT INTO Admin (username, password, nama_lengkap, role) VALUES
('admin', '$2y$10$QslQWAyZOfEqp8EiviRTeu6BI/hnhIj32..j2OeBpmbpxutTMC0QO', 'Admin', 'super_admin'),


(3, 7, 'D15', 45000, 'reguler');

-- Update kapasitas jadwal
UPDATE Jadwal_Tayang SET kapasitas_tersedia = kapasitas_tersedia - 2 WHERE id_tayang = 1;
UPDATE Jadwal_Tayang SET kapasitas_tersedia = kapasitas_tersedia - 3 WHERE id_tayang = 6;
UPDATE Jadwal_Tayang SET kapasitas_tersedia = kapasitas_tersedia - 1 WHERE id_tayang = 7;

-- ============================================
-- CREATE VIEWS
-- ============================================

-- View: Transaksi Lengkap
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

-- View: Detail Tiket
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

-- View: Jadwal Lengkap
CREATE OR REPLACE VIEW v_jadwal_lengkap AS
SELECT 
    jt.id_tayang,
    f.judul_film,
    f.poster_url,
    b.nama_bioskop,
    b.kota,
    jt.nama_tayang,
    jt.tanggal_tayang,
    jt.jam_mulai,
    jt.jam_selesai,
    jt.harga_tiket,
    jt.kapasitas_total,
    jt.kapasitas_tersedia
FROM Jadwal_Tayang jt
JOIN Film f ON jt.id_film = f.id_film
JOIN Bioskop b ON jt.id_bioskop = b.id_bioskop;

-- ============================================
-- STORED PROCEDURES
-- ============================================

-- Procedure: Generate Random Kursi
DELIMITER $$

CREATE PROCEDURE sp_generate_kursi_random(
    IN p_id_jadwal INT,
    OUT p_nomor_kursi VARCHAR(10)
)
BEGIN
    DECLARE v_baris CHAR(1);
    DECLARE v_nomor INT;
    DECLARE v_kursi VARCHAR(10);
    DECLARE v_exists INT;
    DECLARE v_baris_array VARCHAR(10) DEFAULT 'ABCDEFGHIJ';
    DECLARE v_max_attempts INT DEFAULT 100;
    DECLARE v_attempts INT DEFAULT 0;
    
    SET v_exists = 1;
    
    WHILE v_exists = 1 AND v_attempts < v_max_attempts DO
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
        
        SET v_attempts = v_attempts + 1;
    END WHILE;
    
    IF v_attempts >= v_max_attempts THEN
        SET p_nomor_kursi = NULL;
    ELSE
        SET p_nomor_kursi = v_kursi;
    END IF;
END$$

DELIMITER ;

-- ============================================
-- TRIGGERS
-- ============================================

-- Trigger: Auto update kapasitas setelah booking
DELIMITER $$

CREATE TRIGGER trg_after_detail_insert
AFTER INSERT ON Detail_Transaksi
FOR EACH ROW
BEGIN
    UPDATE Jadwal_Tayang 
    SET kapasitas_tersedia = kapasitas_tersedia - 1
    WHERE id_tayang = NEW.id_jadwal_tayang;
END$$

CREATE TRIGGER trg_after_detail_delete
AFTER DELETE ON Detail_Transaksi
FOR EACH ROW
BEGIN
    UPDATE Jadwal_Tayang 
    SET kapasitas_tersedia = kapasitas_tersedia + 1
    WHERE id_tayang = OLD.id_jadwal_tayang;
END$$

DELIMITER ;

-- ============================================
-- QUERY TESTING
-- ============================================

-- Test Query 1: Lihat semua transaksi
SELECT * FROM v_transaksi_lengkap ORDER BY tanggal_transaksi DESC;

-- Test Query 2: Lihat detail tiket
SELECT * FROM v_detail_tiket ORDER BY kode_booking;

-- Test Query 3: Lihat jadwal lengkap
SELECT * FROM v_jadwal_lengkap WHERE tanggal_tayang >= CURDATE() ORDER BY tanggal_tayang, jam_mulai;

-- Test Query 4: Statistik penjualan per film
SELECT 
    f.judul_film,
    COUNT(DISTINCT t.id_transaksi) AS total_transaksi,
    SUM(t.jumlah_tiket) AS total_tiket,
    SUM(t.total_harga) AS total_pendapatan
FROM Film f
LEFT JOIN Jadwal_Tayang jt ON f.id_film = jt.id_film
LEFT JOIN Detail_Transaksi dt ON jt.id_tayang = dt.id_jadwal_tayang
LEFT JOIN Transaksi t ON dt.id_transaksi = t.id_transaksi AND t.status_pembayaran = 'berhasil'
GROUP BY f.id_film
ORDER BY total_pendapatan DESC;


---- ========================================== --------------------
---- DISINI KALAU MAU LIAT AKUN ADMIN : 
---- USERNAME : admin
---- PASSWORD : admin123