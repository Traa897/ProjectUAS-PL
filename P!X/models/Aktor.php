<?php
// models/Aktor.php
require_once 'models/QueryBuilder.php';

class Aktor {
    private $conn;
    private $qb;
    private $table_name = "Aktor";

    public $id_aktor;
    public $nama_aktor;
    public $tanggal_lahir;
    public $negara_asal;
    public $photo_url;

    public function __construct($db) {
        $this->conn = $db;
        $this->qb = new QueryBuilder($db);
    }

    // CREATE
    public function create() {
        $data = [
            'nama_aktor' => htmlspecialchars(strip_tags($this->nama_aktor)),
            'tanggal_lahir' => htmlspecialchars(strip_tags($this->tanggal_lahir)),
            'negara_asal' => htmlspecialchars(strip_tags($this->negara_asal)),
            'photo_url' => htmlspecialchars(strip_tags($this->photo_url))
        ];

        return $this->qb->reset()->table($this->table_name)->insert($data);
    }

    // READ ALL
    public function readAll() {
        $stmt = $this->qb->reset()
            ->table($this->table_name)
            ->select('*')
            ->orderBy('nama_aktor', 'ASC')
            ->get();
        
        return $stmt;
    }

    // READ ONE
    public function readOne() {
        $row = $this->qb->reset()
            ->table($this->table_name)
            ->select('*')
            ->where('id_aktor', '=', $this->id_aktor)
            ->first();

        if ($row) {
            $this->nama_aktor = $row['nama_aktor'];
            $this->tanggal_lahir = $row['tanggal_lahir'];
            $this->negara_asal = $row['negara_asal'];
            $this->photo_url = $row['photo_url'];
            return true;
        }
        
        return false;
    }

    // UPDATE
    public function update() {
        $data = [
            'nama_aktor' => htmlspecialchars(strip_tags($this->nama_aktor)),
            'tanggal_lahir' => htmlspecialchars(strip_tags($this->tanggal_lahir)),
            'negara_asal' => htmlspecialchars(strip_tags($this->negara_asal)),
            'photo_url' => htmlspecialchars(strip_tags($this->photo_url))
        ];

        return $this->qb->reset()
            ->table($this->table_name)
            ->where('id_aktor', '=', htmlspecialchars(strip_tags($this->id_aktor)))
            ->update($data);
    }

    // DELETE
    public function delete() {
        return $this->qb->reset()
            ->table($this->table_name)
            ->where('id_aktor', '=', htmlspecialchars(strip_tags($this->id_aktor)))
            ->delete();
    }

    // COUNT TOTAL
    public function countTotal() {
        return $this->qb->reset()
            ->table($this->table_name)
            ->count();
    }

    // SEARCH
    public function search($keyword) {
        $stmt = $this->qb->reset()
            ->table($this->table_name)
            ->whereLike('nama_aktor', $keyword)
            ->orderBy('nama_aktor', 'ASC')
            ->get();
        
        return $stmt;
    }

    // GET AKTOR WITH FILMS
    public function getAktorWithFilms($id_aktor) {
        $stmt = $this->qb->reset()
            ->table($this->table_name . ' a')
            ->select('a.*, GROUP_CONCAT(f.judul_film SEPARATOR ", ") as daftar_film')
            ->leftJoin('Film_Aktor fa', 'a.id_aktor', '=', 'fa.id_aktor')
            ->leftJoin('Film f', 'fa.id_film', '=', 'f.id_film')
            ->where('a.id_aktor', '=', $id_aktor)
            ->groupBy('a.id_aktor')
            ->first();
        
        return $stmt;
    }
}
?>