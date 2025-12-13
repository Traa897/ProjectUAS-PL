<?php
// models/Film.php - PERBAIKAN: Hanya method yang perlu diubah

// 1. PERBAIKAN: readAll - Hanya tampil film yang PUNYA JADWAL
public function readAll() {
    $query = "SELECT 
                f.id_film, 
                f.judul_film, 
                f.tahun_rilis, 
                f.durasi_menit, 
                f.sipnosis, 
                f.rating, 
                f.poster_url, 
                f.id_genre, 
                g.nama_genre
              FROM Film f
              LEFT JOIN Genre g ON f.id_genre = g.id_genre
              INNER JOIN Jadwal_Tayang jt ON f.id_film = jt.id_film
              GROUP BY f.id_film
              ORDER BY f.tahun_rilis DESC, f.id_film ASC";
    
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    
    return $stmt;
}

// 2. PERBAIKAN: readByGenre - Hanya tampil film yang PUNYA JADWAL
public function readByGenre($id_genre) {
    $query = "SELECT 
                f.id_film, 
                f.judul_film, 
                f.tahun_rilis, 
                f.durasi_menit, 
                f.sipnosis, 
                f.rating, 
                f.poster_url, 
                f.id_genre, 
                g.nama_genre
              FROM Film f
              LEFT JOIN Genre g ON f.id_genre = g.id_genre
              INNER JOIN Jadwal_Tayang jt ON f.id_film = jt.id_film
              WHERE f.id_genre = :id_genre
              GROUP BY f.id_film
              ORDER BY f.tahun_rilis DESC, f.id_film ASC";
    
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':id_genre', $id_genre);
    $stmt->execute();
    
    return $stmt;
}

// 3. PERBAIKAN: getFilmStatus - Hanya 2 status: SEDANG TAYANG dan AKAN TAYANG
public function getFilmStatus($id_film) {
    // Cek apakah sedang tayang (ada jadwal yang belum selesai hari ini)
    $query = "SELECT COUNT(*) as count FROM Jadwal_Tayang 
              WHERE id_film = :id_film 
              AND CONCAT(tanggal_tayang, ' ', jam_selesai) >= NOW()
              AND tanggal_tayang <= CURDATE()";
    
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':id_film', $id_film);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if($result['count'] > 0) {
        return 'Sedang Tayang';
    }
    
    // Cek apakah akan tayang (ada jadwal di masa depan) - INI PRE-SALE
    $query = "SELECT COUNT(*) as count FROM Jadwal_Tayang 
              WHERE id_film = :id_film 
              AND tanggal_tayang > CURDATE()";
    
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':id_film', $id_film);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if($result['count'] > 0) {
        return 'Akan Tayang'; // Pre-Sale
    }
    
    // Jika tidak ada status, kembalikan null (film ini tidak akan muncul karena INNER JOIN)
    return null;
}

// 4. PERBAIKAN: readSedangTayang
public function readSedangTayang() {
    $query = "SELECT 
                f.id_film, 
                f.judul_film, 
                f.tahun_rilis, 
                f.durasi_menit, 
                f.sipnosis, 
                f.rating, 
                f.poster_url, 
                f.id_genre, 
                g.nama_genre
              FROM Film f
              LEFT JOIN Genre g ON f.id_genre = g.id_genre
              INNER JOIN Jadwal_Tayang jt ON f.id_film = jt.id_film
              WHERE CONCAT(jt.tanggal_tayang, ' ', jt.jam_selesai) >= NOW()
                AND jt.tanggal_tayang <= CURDATE()
              GROUP BY f.id_film
              ORDER BY f.tahun_rilis DESC, f.id_film ASC";
    
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt;
}

// 5. PERBAIKAN: readAkanTayang - Ini untuk PRE-SALE
public function readAkanTayang() {
    $query = "SELECT 
                f.id_film, 
                f.judul_film, 
                f.tahun_rilis, 
                f.durasi_menit, 
                f.sipnosis, 
                f.rating, 
                f.poster_url, 
                f.id_genre, 
                g.nama_genre
              FROM Film f
              LEFT JOIN Genre g ON f.id_genre = g.id_genre
              INNER JOIN Jadwal_Tayang jt ON f.id_film = jt.id_film
              WHERE jt.tanggal_tayang > CURDATE()
              GROUP BY f.id_film
              ORDER BY f.tahun_rilis DESC, f.id_film ASC";
    
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt;
}

// 6. HAPUS method readTelahTayang - Tidak diperlukan lagi
// Method countByStatus tetap sama, tapi hanya untuk akan_tayang dan sedang_tayang
public function countByStatus($status) {
    switch($status) {
        case 'akan_tayang':
            $stmt = $this->readAkanTayang();
            break;
        case 'sedang_tayang':
            $stmt = $this->readSedangTayang();
            break;
        default:
            return 0;
    }
    return $stmt->rowCount();
}