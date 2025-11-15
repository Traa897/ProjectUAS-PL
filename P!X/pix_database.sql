-- ============================================
-- DATABASE FILM - CREATE TABLES
-- ============================================

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