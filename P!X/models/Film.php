<?php
// models/Film.php - COMPLETE FINAL FIX

require_once 'models/BaseModel.php';

class Film extends BaseModel {
    use Searchable;
    
    public $id_film;
    public $judul_film;
    public $tahun_rilis;
    public $durasi_menit;
    public $sipnosis;
    public $rating;
    public $poster_url;
    public $id_genre;
    public $nama_genre;

    protected function getTableName() {
        return "Film";
    }
    
    protected function getPrimaryKey() {
        return "id_film";
    }
    
    protected function getSearchableFields() {
        return ['judul_film', 'sipnosis'];
    }
    
    protected function prepareData() {
        return [
            'judul_film' => $this->sanitize($this->judul_film),
            'tahun_rilis' => $this->sanitize($this->tahun_rilis),
            'durasi_menit' => $this->sanitize($this->durasi_menit),
            'sipnosis' => $this->sanitize($this->sipnosis),
            'rating' => $this->sanitize($this->rating),
            'poster_url' => $this->sanitize($this->poster_url),
            'id_genre' => $this->sanitize($this->id_genre)
        ];
    }
    
    // ✅ METHOD 1: readAll() - UNTUK PUBLIC/USER
    // HANYA film yang punya jadwal >= HARI INI (tidak termasuk jadwal lampau)
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
                  WHERE EXISTS (
                      SELECT 1 FROM Jadwal_Tayang jt 
                      WHERE jt.id_film = f.id_film
                      AND jt.tanggal_tayang >= CURDATE()
                  )
                  GROUP BY f.id_film, f.judul_film, f.tahun_rilis, f.durasi_menit, 
                           f.sipnosis, f.rating, f.poster_url, f.id_genre, g.nama_genre
                  ORDER BY f.tahun_rilis DESC, f.id_film ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt;
    }
    
    // ✅ METHOD 2: readAllIncludingNoSchedule() - UNTUK ADMIN DASHBOARD
    // SEMUA film termasuk yang BELUM ada jadwal atau jadwal sudah lewat
    public function readAllIncludingNoSchedule() {
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
                  ORDER BY f.created_at DESC, f.id_film DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt;
    }

    // ✅ readByGenre - HANYA film dengan jadwal >= hari ini
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
                  WHERE f.id_genre = :id_genre
                    AND EXISTS (
                        SELECT 1 FROM Jadwal_Tayang jt 
                        WHERE jt.id_film = f.id_film
                        AND jt.tanggal_tayang >= CURDATE()
                    )
                  GROUP BY f.id_film, f.judul_film, f.tahun_rilis, f.durasi_menit, 
                           f.sipnosis, f.rating, f.poster_url, f.id_genre, g.nama_genre
                  ORDER BY f.tahun_rilis DESC, f.id_film ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_genre', $id_genre);
        $stmt->execute();
        
        return $stmt;
    }
    
    // ✅ getFilmStatus - Cek status film (HANYA jadwal >= hari ini)
    public function getFilmStatus($id_film) {
        // PRIORITAS 1: Cek apakah sedang tayang (ada jadwal hari ini yang belum selesai)
        $query = "SELECT COUNT(*) as count FROM Jadwal_Tayang 
                  WHERE id_film = :id_film 
                  AND CONCAT(tanggal_tayang, ' ', jam_selesai) >= NOW()
                  AND tanggal_tayang = CURDATE()";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_film', $id_film);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($result['count'] > 0) {
            return 'Sedang Tayang';
        }
        
        // PRIORITAS 2: Cek jadwal masa depan (>= besok)
        $query = "SELECT COUNT(*) as count FROM Jadwal_Tayang 
                  WHERE id_film = :id_film 
                  AND tanggal_tayang > CURDATE()";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_film', $id_film);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($result['count'] > 0) {
            return 'Akan Tayang';
        }
        
        // Jika tidak ada jadwal yang valid (>= hari ini), return null
        return null;
    }

    // ✅ readSedangTayang - Film dengan jadwal hari ini
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
                  WHERE EXISTS (
                      SELECT 1 FROM Jadwal_Tayang jt 
                      WHERE jt.id_film = f.id_film
                        AND CONCAT(jt.tanggal_tayang, ' ', jt.jam_selesai) >= NOW()
                        AND jt.tanggal_tayang = CURDATE()
                  )
                  GROUP BY f.id_film, f.judul_film, f.tahun_rilis, f.durasi_menit, 
                           f.sipnosis, f.rating, f.poster_url, f.id_genre, g.nama_genre
                  ORDER BY f.tahun_rilis DESC, f.id_film ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // ✅ readAkanTayang - Film dengan jadwal >= besok
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
                  WHERE EXISTS (
                      SELECT 1 FROM Jadwal_Tayang jt 
                      WHERE jt.id_film = f.id_film
                        AND jt.tanggal_tayang > CURDATE()
                  )
                  GROUP BY f.id_film, f.judul_film, f.tahun_rilis, f.durasi_menit, 
                           f.sipnosis, f.rating, f.poster_url, f.id_genre, g.nama_genre
                  ORDER BY f.tahun_rilis DESC, f.id_film ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

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

    public function readOne() {
        $row = $this->qb->reset()
            ->table($this->getTableName() . ' f')
            ->select('f.*, g.nama_genre')
            ->leftJoin('Genre g', 'f.id_genre', '=', 'g.id_genre')
            ->where('f.id_film', '=', $this->id_film)
            ->first();

        if ($row) {
            $this->populateFromArray($row);
            return true;
        }
        
        return false;
    }

    // ✅ search - HANYA film dengan jadwal >= hari ini
    public function search($keyword, $fields = []) {
        if (empty($fields)) {
            $fields = $this->getSearchableFields();
        }
        
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
                  WHERE (f.judul_film LIKE :keyword OR f.sipnosis LIKE :keyword)
                    AND EXISTS (
                        SELECT 1 FROM Jadwal_Tayang jt 
                        WHERE jt.id_film = f.id_film
                        AND jt.tanggal_tayang >= CURDATE()
                    )
                  GROUP BY f.id_film, f.judul_film, f.tahun_rilis, f.durasi_menit, 
                           f.sipnosis, f.rating, f.poster_url, f.id_genre, g.nama_genre
                  ORDER BY f.tahun_rilis DESC, f.id_film ASC";
        
        $stmt = $this->conn->prepare($query);
        $searchKeyword = "%$keyword%";
        $stmt->bindParam(':keyword', $searchKeyword);
        $stmt->execute();
        
        return $stmt;
    }
}
?>