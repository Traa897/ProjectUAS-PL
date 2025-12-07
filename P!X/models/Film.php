<?php
// models/Film.php - FIXED VERSION - Anti Duplikasi
require_once 'models/QueryBuilder.php';

class Film {
    private $conn;
    private $qb;
    private $table_name = "Film";

    public $id_film;
    public $judul_film;
    public $tahun_rilis;
    public $durasi_menit;
    public $sipnosis;
    public $rating;
    public $poster_url;
    public $id_genre;

    public function __construct($db) {
        $this->conn = $db;
        $this->qb = new QueryBuilder($db);
    }

    // CREATE
    public function create() {
        $data = [
            'judul_film' => htmlspecialchars(strip_tags($this->judul_film)),
            'tahun_rilis' => htmlspecialchars(strip_tags($this->tahun_rilis)),
            'durasi_menit' => htmlspecialchars(strip_tags($this->durasi_menit)),
            'sipnosis' => htmlspecialchars(strip_tags($this->sipnosis)),
            'rating' => htmlspecialchars(strip_tags($this->rating)),
            'poster_url' => htmlspecialchars(strip_tags($this->poster_url)),
            'id_genre' => htmlspecialchars(strip_tags($this->id_genre))
        ];

        return $this->qb->reset()->table($this->table_name)->insert($data);
    }

    // READ ALL - FIXED: Query manual dengan DISTINCT dan GROUP BY
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
                  FROM {$this->table_name} f
                  LEFT JOIN Genre g ON f.id_genre = g.id_genre
                  GROUP BY f.id_film
                  ORDER BY f.tahun_rilis DESC, f.id_film ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt;
    }

    // READ ONE
    public function readOne() {
        $row = $this->qb->reset()
            ->table($this->table_name . ' f')
            ->select('f.*, g.nama_genre')
            ->leftJoin('Genre g', 'f.id_genre', '=', 'g.id_genre')
            ->where('f.id_film', '=', $this->id_film)
            ->first();

        if ($row) {
            $this->judul_film = $row['judul_film'];
            $this->tahun_rilis = $row['tahun_rilis'];
            $this->durasi_menit = $row['durasi_menit'];
            $this->sipnosis = $row['sipnosis'];
            $this->rating = $row['rating'];
            $this->poster_url = $row['poster_url'];
            $this->id_genre = $row['id_genre'];
            return true;
        }
        
        return false;
    }

    // READ BY GENRE - FIXED
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
                  FROM {$this->table_name} f
                  LEFT JOIN Genre g ON f.id_genre = g.id_genre
                  WHERE f.id_genre = :id_genre
                  GROUP BY f.id_film
                  ORDER BY f.tahun_rilis DESC, f.id_film ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_genre', $id_genre);
        $stmt->execute();
        
        return $stmt;
    }

    // SEARCH - FIXED
    public function search($keyword) {
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
                  FROM {$this->table_name} f
                  LEFT JOIN Genre g ON f.id_genre = g.id_genre
                  WHERE f.judul_film LIKE :keyword
                  GROUP BY f.id_film
                  ORDER BY f.tahun_rilis DESC, f.id_film ASC";
        
        $stmt = $this->conn->prepare($query);
        $searchParam = "%$keyword%";
        $stmt->bindParam(':keyword', $searchParam);
        $stmt->execute();
        
        return $stmt;
    }

    // GET FILM STATUS - FIXED
    public function getFilmStatus($id_film) {
        // Cek apakah sedang tayang (dalam 7 hari terakhir sampai hari ini)
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
        
        // Cek apakah akan tayang (tanggal di masa depan)
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

    // UPDATE
    public function update() {
        $data = [
            'judul_film' => htmlspecialchars(strip_tags($this->judul_film)),
            'tahun_rilis' => htmlspecialchars(strip_tags($this->tahun_rilis)),
            'durasi_menit' => htmlspecialchars(strip_tags($this->durasi_menit)),
            'sipnosis' => htmlspecialchars(strip_tags($this->sipnosis)),
            'rating' => htmlspecialchars(strip_tags($this->rating)),
            'poster_url' => htmlspecialchars(strip_tags($this->poster_url)),
            'id_genre' => htmlspecialchars(strip_tags($this->id_genre))
        ];

        return $this->qb->reset()
            ->table($this->table_name)
            ->where('id_film', '=', htmlspecialchars(strip_tags($this->id_film)))
            ->update($data);
    }

    // DELETE
    public function delete() {
        return $this->qb->reset()
            ->table($this->table_name)
            ->where('id_film', '=', htmlspecialchars(strip_tags($this->id_film)))
            ->delete();
    }

    // COUNT TOTAL
    public function countTotal() {
        return $this->qb->reset()
            ->table($this->table_name)
            ->count();
    }

    // AVERAGE RATING
    public function averageRating() {
        return $this->qb->reset()
            ->table($this->table_name)
            ->avg('rating');
    }

    // GET TOP RATED - FIXED
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
                  FROM {$this->table_name} f
                  LEFT JOIN Genre g ON f.id_genre = g.id_genre
                  GROUP BY f.id_film
                  ORDER BY f.rating DESC, f.id_film ASC
                  LIMIT :limit";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt;
    }

    // READ FILMS BY STATUS - FIXED dengan logika yang lebih akurat
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
                  FROM {$this->table_name} f
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
                  FROM {$this->table_name} f
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
                  FROM {$this->table_name} f
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

    // Count films by status
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