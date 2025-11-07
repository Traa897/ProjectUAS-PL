<?php
// models/Movie.php
// REPLACE FILE INI DENGAN KODE DI BAWAH

require_once 'models/QueryBuilder.php';

class Movie {
    private $conn;
    private $qb;
    private $table_name = "movies";

    public $id;
    public $title;
    public $director;
    public $genre_id;
    public $duration;
    public $release_date;
    public $status;
    public $rating;
    public $synopsis;
    public $poster_url;

    public function __construct($db) {
        $this->conn = $db;
        $this->qb = new QueryBuilder($db);
    }

    public function create() {
        $data = [
            'title' => htmlspecialchars(strip_tags($this->title)),
            'director' => htmlspecialchars(strip_tags($this->director)),
            'genre_id' => htmlspecialchars(strip_tags($this->genre_id)),
            'duration' => htmlspecialchars(strip_tags($this->duration)),
            'release_date' => htmlspecialchars(strip_tags($this->release_date)),
            'status' => htmlspecialchars(strip_tags($this->status)),
            'rating' => htmlspecialchars(strip_tags($this->rating)),
            'synopsis' => htmlspecialchars(strip_tags($this->synopsis)),
            'poster_url' => htmlspecialchars(strip_tags($this->poster_url))
        ];

        return $this->qb->table($this->table_name)->insert($data);
    }

    public function readAll() {
        $stmt = $this->qb->reset()
            ->table($this->table_name . ' m')
            ->select('m.*, g.name as genre_name')
            ->leftJoin('genres g', 'm.genre_id', '=', 'g.id')
            ->orderBy('m.release_date', 'DESC')
            ->get();
        
        return $stmt;
    }

    public function readByStatus($status) {
        $stmt = $this->qb->reset()
            ->table($this->table_name . ' m')
            ->select('m.*, g.name as genre_name')
            ->leftJoin('genres g', 'm.genre_id', '=', 'g.id')
            ->where('m.status', '=', $status)
            ->orderBy('m.release_date', 'DESC')
            ->get();
        
        return $stmt;
    }

    public function readByGenre($genre_id) {
        $stmt = $this->qb->reset()
            ->table($this->table_name . ' m')
            ->select('m.*, g.name as genre_name')
            ->leftJoin('genres g', 'm.genre_id', '=', 'g.id')
            ->where('m.genre_id', '=', $genre_id)
            ->orderBy('m.release_date', 'DESC')
            ->get();
        
        return $stmt;
    }

    public function readOne() {
        $row = $this->qb->reset()
            ->table($this->table_name . ' m')
            ->select('m.*, g.name as genre_name')
            ->leftJoin('genres g', 'm.genre_id', '=', 'g.id')
            ->where('m.id', '=', $this->id)
            ->first();

        if ($row) {
            $this->title = $row['title'];
            $this->director = $row['director'];
            $this->genre_id = $row['genre_id'];
            $this->duration = $row['duration'];
            $this->release_date = $row['release_date'];
            $this->status = $row['status'];
            $this->rating = $row['rating'];
            $this->synopsis = $row['synopsis'];
            $this->poster_url = $row['poster_url'];
            return true;
        }
        
        return false;
    }

    public function update() {
        $data = [
            'title' => htmlspecialchars(strip_tags($this->title)),
            'director' => htmlspecialchars(strip_tags($this->director)),
            'genre_id' => htmlspecialchars(strip_tags($this->genre_id)),
            'duration' => htmlspecialchars(strip_tags($this->duration)),
            'release_date' => htmlspecialchars(strip_tags($this->release_date)),
            'status' => htmlspecialchars(strip_tags($this->status)),
            'rating' => htmlspecialchars(strip_tags($this->rating)),
            'synopsis' => htmlspecialchars(strip_tags($this->synopsis)),
            'poster_url' => htmlspecialchars(strip_tags($this->poster_url))
        ];

        return $this->qb->reset()
            ->table($this->table_name)
            ->where('id', '=', htmlspecialchars(strip_tags($this->id)))
            ->update($data);
    }

    public function delete() {
        return $this->qb->reset()
            ->table($this->table_name)
            ->where('id', '=', htmlspecialchars(strip_tags($this->id)))
            ->delete();
    }

    public function search($keyword) {
        $stmt = $this->qb->reset()
            ->table($this->table_name . ' m')
            ->select('m.*, g.name as genre_name')
            ->leftJoin('genres g', 'm.genre_id', '=', 'g.id')
            ->whereLike('m.title', $keyword)
            ->orWhereLike('m.director', $keyword)
            ->orderBy('m.release_date', 'DESC')
            ->get();
        
        return $stmt;
    }

    public function countTotal() {
        return $this->qb->reset()
            ->table($this->table_name)
            ->count();
    }

    public function countByStatus($status) {
        return $this->qb->reset()
            ->table($this->table_name)
            ->where('status', '=', $status)
            ->count();
    }

    public function averageRating() {
        return $this->qb->reset()
            ->table($this->table_name)
            ->avg('rating');
    }

    public function getLatest($limit = 5) {
        $stmt = $this->qb->reset()
            ->table($this->table_name . ' m')
            ->select('m.*, g.name as genre_name')
            ->leftJoin('genres g', 'm.genre_id', '=', 'g.id')
            ->orderBy('m.created_at', 'DESC')
            ->limit($limit)
            ->get();
        
        return $stmt;
    }

    public function getTopRated($limit = 5) {
        $stmt = $this->qb->reset()
            ->table($this->table_name . ' m')
            ->select('m.*, g.name as genre_name')
            ->leftJoin('genres g', 'm.genre_id', '=', 'g.id')
            ->orderBy('m.rating', 'DESC')
            ->limit($limit)
            ->get();
        
        return $stmt;
    }
}
?>