<?php
// models/Film.php - REFACTORED dengan OOP
require_once 'models/BaseModel.php';


class Film extends BaseModel {
    use Searchable; // Menggunakan trait untuk search functionality
    
    // Properties
    public $id_film;
    public $judul_film;
    public $tahun_rilis;
    public $durasi_menit;
    public $sipnosis;
    public $rating;
    public $poster_url;
    public $id_genre;

    // Implementation of abstract methods dari BaseModel
    protected function getTableName() {
        return "Film";
    }
    
    protected function getPrimaryKey() {
        return "id_film";
    }
    
    // Implementation untuk Searchable trait
    protected function getSearchableFields() {
        return ['judul_film', 'sipnosis'];
    }
    
    // Implementation of abstract method prepareData
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
    
    // Polymorphism - Override readOne dari parent
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
    
    // Polymorphism - Override readAll dengan JOIN
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
                  FROM {$this->getTableName()} f
                  LEFT JOIN Genre g ON f.id_genre = g.id_genre
                  GROUP BY f.id_film
                  ORDER BY f.tahun_rilis DESC, f.id_film ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt;
    }

    // Specific methods untuk Film
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
                  FROM {$this->getTableName()} f
                  LEFT JOIN Genre g ON f.id_genre = g.id_genre
                  WHERE f.id_genre = :id_genre
                  GROUP BY f.id_film
                  ORDER BY f.tahun_rilis DESC, f.id_film ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_genre', $id_genre);
        $stmt->execute();
        
        return $stmt;
    }

    public function getFilmStatus($id_film) {
        // Cek apakah sedang tayang
        $query = "SELECT COUNT(*) as count FROM Jadwal_Tayang 
                  WHERE id_film = :id_film 
                  AND tanggal_tayang <= CURDATE() 
                  AND tanggal_tayang >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_film', $id_film);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($result['count'] > 0) {
            return 'Sedang Tayang';
        }
        
        // Cek apakah akan tayang
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
        
        return null;
    }

    public function averageRating() {
        return $this->qb->reset()
            ->table($this->getTableName())
            ->avg('rating');
    }

    public function getTopRated($limit = 5) {
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
                  FROM {$this->getTableName()} f
                  LEFT JOIN Genre g ON f.id_genre = g.id_genre
                  GROUP BY f.id_film
                  ORDER BY f.rating DESC, f.id_film ASC
                  LIMIT :limit";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt;
    }

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
                  FROM {$this->getTableName()} f
                  LEFT JOIN Genre g ON f.id_genre = g.id_genre
                  INNER JOIN Jadwal_Tayang jt ON f.id_film = jt.id_film
                  WHERE jt.tanggal_tayang > CURDATE()
                    AND NOT EXISTS (
                        SELECT 1 FROM Jadwal_Tayang jt2 
                        WHERE jt2.id_film = f.id_film 
                        AND jt2.tanggal_tayang <= CURDATE()
                    )
                  GROUP BY f.id_film
                  ORDER BY f.tahun_rilis DESC, f.id_film ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

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
                  FROM {$this->getTableName()} f
                  LEFT JOIN Genre g ON f.id_genre = g.id_genre
                  INNER JOIN Jadwal_Tayang jt ON f.id_film = jt.id_film
                  WHERE CONCAT(jt.tanggal_tayang, ' ', jt.jam_selesai) >= NOW()
                    AND jt.tanggal_tayang <= DATE_ADD(CURDATE(), INTERVAL 7 DAY)
                  GROUP BY f.id_film
                  ORDER BY f.tahun_rilis DESC, f.id_film ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function readTelahTayang() {
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
                  FROM {$this->getTableName()} f
                  LEFT JOIN Genre g ON f.id_genre = g.id_genre
                  LEFT JOIN Jadwal_Tayang jt ON f.id_film = jt.id_film
                  WHERE NOT EXISTS (
                        SELECT 1 FROM Jadwal_Tayang jt2 
                        WHERE jt2.id_film = f.id_film 
                        AND CONCAT(jt2.tanggal_tayang, ' ', jt2.jam_selesai) >= NOW()
                    )
                  GROUP BY f.id_film
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
            case 'telah_tayang':
                $stmt = $this->readTelahTayang();
                break;
            default:
                return 0;
        }
        return $stmt->rowCount();
    }
}
?>