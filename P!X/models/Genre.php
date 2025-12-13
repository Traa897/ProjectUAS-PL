<?php
// models/Genre.php
require_once 'models/QueryBuilder.php';
require_once 'models/BaseModel.php';

class Genre {
    private $conn;
    private $qb;
    private $table_name = "Genre";

    public $id_genre;
    public $nama_genre;
    public $deskripsi;

    public function __construct($db) {
        $this->conn = $db;
        $this->qb = new QueryBuilder($db);
    }

    // CREATE
    public function create() {
        $data = [
            'nama_genre' => htmlspecialchars(strip_tags($this->nama_genre)),
            'deskripsi' => htmlspecialchars(strip_tags($this->deskripsi))
        ];

        return $this->qb->reset()->table($this->table_name)->insert($data);
    }

    // READ ALL
    public function readAll() {
        $stmt = $this->qb->reset()
            ->table($this->table_name)
            ->select('*')
            ->orderBy('nama_genre', 'ASC')
            ->get();
        
        return $stmt;
    }

    // READ ONE
    public function readOne() {
        $row = $this->qb->reset()
            ->table($this->table_name)
            ->select('*')
            ->where('id_genre', '=', $this->id_genre)
            ->first();

        if ($row) {
            $this->nama_genre = $row['nama_genre'];
            $this->deskripsi = $row['deskripsi'];
            return true;
        }
        
        return false;
    }

    // UPDATE
    public function update() {
        $data = [
            'nama_genre' => htmlspecialchars(strip_tags($this->nama_genre)),
            'deskripsi' => htmlspecialchars(strip_tags($this->deskripsi))
        ];

        return $this->qb->reset()
            ->table($this->table_name)
            ->where('id_genre', '=', htmlspecialchars(strip_tags($this->id_genre)))
            ->update($data);
    }

    // DELETE
    public function delete() {
        return $this->qb->reset()
            ->table($this->table_name)
            ->where('id_genre', '=', htmlspecialchars(strip_tags($this->id_genre)))
            ->delete();
    }

    // COUNT MOVIES BY GENRE
    public function countMovies() {
        $stmt = $this->qb->reset()
            ->table($this->table_name . ' g')
            ->select('g.*, COUNT(f.id_film) as total_film')
            ->leftJoin('Film f', 'g.id_genre', '=', 'f.id_genre')
            ->groupBy('g.id_genre')
            ->orderBy('g.nama_genre', 'ASC')
            ->get();
        
        return $stmt;
    }

    // COUNT TOTAL
    public function countTotal() {
        return $this->qb->reset()
            ->table($this->table_name)
            ->count();
    }
}
?>