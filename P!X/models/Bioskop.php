<?php
// models/Bioskop.php
require_once 'models/QueryBuilder.php';

class Bioskop {
    private $conn;
    private $qb;
    private $table_name = "Bioskop";

    public $id_bioskop;
    public $nama_bioskop;
    public $kota;
    public $alamat_bioskop;
    public $jumlah_studio;

    public function __construct($db) {
        $this->conn = $db;
        $this->qb = new QueryBuilder($db);
    }

    // CREATE
    public function create() {
        $data = [
            'nama_bioskop' => htmlspecialchars(strip_tags($this->nama_bioskop)),
            'kota' => htmlspecialchars(strip_tags($this->kota)),
            'alamat_bioskop' => htmlspecialchars(strip_tags($this->alamat_bioskop)),
            'jumlah_studio' => htmlspecialchars(strip_tags($this->jumlah_studio))
        ];

        return $this->qb->reset()->table($this->table_name)->insert($data);
    }

    // READ ALL
    public function readAll() {
        $stmt = $this->qb->reset()
            ->table($this->table_name)
            ->select('*')
            ->orderBy('nama_bioskop', 'ASC')
            ->get();
        
        return $stmt;
    }

    // READ BY CITY
    public function readByCity($kota) {
        $stmt = $this->qb->reset()
            ->table($this->table_name)
            ->where('kota', '=', $kota)
            ->orderBy('nama_bioskop', 'ASC')
            ->get();
        
        return $stmt;
    }

    // READ ONE
    public function readOne() {
        $row = $this->qb->reset()
            ->table($this->table_name)
            ->select('*')
            ->where('id_bioskop', '=', $this->id_bioskop)
            ->first();

        if ($row) {
            $this->nama_bioskop = $row['nama_bioskop'];
            $this->kota = $row['kota'];
            $this->alamat_bioskop = $row['alamat_bioskop'];
            $this->jumlah_studio = $row['jumlah_studio'];
            return true;
        }
        
        return false;
    }

    // UPDATE
    public function update() {
        $data = [
            'nama_bioskop' => htmlspecialchars(strip_tags($this->nama_bioskop)),
            'kota' => htmlspecialchars(strip_tags($this->kota)),
            'alamat_bioskop' => htmlspecialchars(strip_tags($this->alamat_bioskop)),
            'jumlah_studio' => htmlspecialchars(strip_tags($this->jumlah_studio))
        ];

        return $this->qb->reset()
            ->table($this->table_name)
            ->where('id_bioskop', '=', htmlspecialchars(strip_tags($this->id_bioskop)))
            ->update($data);
    }

    // DELETE
    public function delete() {
        return $this->qb->reset()
            ->table($this->table_name)
            ->where('id_bioskop', '=', htmlspecialchars(strip_tags($this->id_bioskop)))
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
            ->whereLike('nama_bioskop', $keyword)
            ->orWhereLike('kota', $keyword)
            ->orderBy('nama_bioskop', 'ASC')
            ->get();
        
        return $stmt;
    }

    // GET BIOSKOP WITH SCHEDULES
    public function getBioskopWithSchedules($id_bioskop) {
        $stmt = $this->qb->reset()
            ->table($this->table_name . ' b')
            ->select('b.*, COUNT(jt.id_tayang) as total_jadwal')
            ->leftJoin('Jadwal_Tayang jt', 'b.id_bioskop', '=', 'jt.id_bioskop')
            ->where('b.id_bioskop', '=', $id_bioskop)
            ->groupBy('b.id_bioskop')
            ->first();
        
        return $stmt;
    }
}
?>