<?php
// models/Film.php - COMPLETE FIXED VERSION
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
    
    // PERBAIKAN 1: readAll - Hanya tampil film yang PUNYA JADWAL (untuk Public/User)
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
    
    // NEW: readAllIncludingNoSchedule - SEMUA FILM termasuk tanpa jadwal (untuk Admin)
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
                  ORDER BY f.tahun_rilis DESC, f.id_film ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt;
    }

    // PERBAIKAN 2: readByGenre - Hanya tampil film yang PUNYA JADWAL (untuk Public/User)
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
    
    // NEW: readByGenreAll - SEMUA FILM di genre ini termasuk tanpa jadwal (untuk Admin)
    public function readByGenreAll($id_genre) {
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
                  ORDER BY f.tahun_rilis DESC, f.id_film ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_genre', $id_genre);
        $stmt->execute();
        
        return $stmt;
    }

    // PERBAIKAN 3: getFilmStatus - Hanya 2 status: SEDANG TAYANG dan AKAN TAYANG
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
        
        // Jika tidak ada status, kembalikan null
        return null;
    }

    // PERBAIKAN 4: readSedangTayang
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

    // PERBAIKAN 5: readAkanTayang - Ini untuk PRE-SALE
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

    // TIDAK PERLU readTelahTayang - Dihapus
    
    // countByStatus - Hanya untuk akan_tayang dan sedang_tayang
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

    // readOne - Override untuk mendapatkan nama genre
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

    // search - Override untuk hanya tampilkan film dengan jadwal (untuk Public/User)
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
                  INNER JOIN Jadwal_Tayang jt ON f.id_film = jt.id_film
                  WHERE (f.judul_film LIKE :keyword OR f.sipnosis LIKE :keyword)
                  GROUP BY f.id_film
                  ORDER BY f.tahun_rilis DESC, f.id_film ASC";
        
        $stmt = $this->conn->prepare($query);
        $searchKeyword = "%$keyword%";
        $stmt->bindParam(':keyword', $searchKeyword);
        $stmt->execute();
        
        return $stmt;
    }
    
    // NEW: searchAllFilms - Search SEMUA FILM termasuk tanpa jadwal (untuk Admin)
    public function searchAllFilms($keyword) {
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
                  ORDER BY f.tahun_rilis DESC, f.id_film ASC";
        
        $stmt = $this->conn->prepare($query);
        $searchKeyword = "%$keyword%";
        $stmt->bindParam(':keyword', $searchKeyword);
        $stmt->execute();
        
        return $stmt;
    }
}
?>