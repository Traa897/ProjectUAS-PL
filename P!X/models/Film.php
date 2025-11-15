<?php
// models/Film.php
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

    // READ ALL
    public function readAll() {
        $stmt = $this->qb->reset()
            ->table($this->table_name . ' f')
            ->select('f.*, g.nama_genre')
            ->leftJoin('Genre g', 'f.id_genre', '=', 'g.id_genre')
            ->orderBy('f.tahun_rilis', 'DESC')
            ->get();
        
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

    // READ BY GENRE
    public function readByGenre($id_genre) {
        $stmt = $this->qb->reset()
            ->table($this->table_name . ' f')
            ->select('f.*, g.nama_genre')
            ->leftJoin('Genre g', 'f.id_genre', '=', 'g.id_genre')
            ->where('f.id_genre', '=', $id_genre)
            ->orderBy('f.tahun_rilis', 'DESC')
            ->get();
        
        return $stmt;
    }

    // SEARCH
    public function search($keyword) {
        $stmt = $this->qb->reset()
            ->table($this->table_name . ' f')
            ->select('f.*, g.nama_genre')
            ->leftJoin('Genre g', 'f.id_genre', '=', 'g.id_genre')
            ->whereLike('f.judul_film', $keyword)
            ->orderBy('f.tahun_rilis', 'DESC')
            ->get();
        
        return $stmt;
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

    // GET TOP RATED
    public function getTopRated($limit = 5) {
        $stmt = $this->qb->reset()
            ->table($this->table_name . ' f')
            ->select('f.*, g.nama_genre')
            ->leftJoin('Genre g', 'f.id_genre', '=', 'g.id_genre')
            ->orderBy('f.rating', 'DESC')
            ->limit($limit)
            ->get();
        
        return $stmt;
    }

    // GET FILM WITH ACTORS
    public function getFilmWithActors($id_film) {
        $stmt = $this->qb->reset()
            ->table($this->table_name . ' f')
            ->select('f.*, g.nama_genre, GROUP_CONCAT(a.nama_aktor SEPARATOR ", ") as daftar_aktor')
            ->leftJoin('Genre g', 'f.id_genre', '=', 'g.id_genre')
            ->leftJoin('Film_Aktor fa', 'f.id_film', '=', 'fa.id_film')
            ->leftJoin('Aktor a', 'fa.id_aktor', '=', 'a.id_aktor')
            ->where('f.id_film', '=', $id_film)
            ->groupBy('f.id_film')
            ->first();
        
        return $stmt;
    }
}
?>