<?php
// models/Jadwal.php
require_once 'models/QueryBuilder.php';

class Jadwal {
    private $conn;
    private $qb;
    private $table_name = "Jadwal_Tayang";

    public $id_tayang;
    public $id_film;
    public $id_bioskop;
    public $nama_tayang;
    public $tanggal_tayang;
    public $jam_mulai;
    public $jam_selesai;
    public $harga_tiket;

    public function __construct($db) {
        $this->conn = $db;
        $this->qb = new QueryBuilder($db);
    }

    // CREATE
    public function create() {
        $data = [
            'id_film' => htmlspecialchars(strip_tags($this->id_film)),
            'id_bioskop' => htmlspecialchars(strip_tags($this->id_bioskop)),
            'nama_tayang' => htmlspecialchars(strip_tags($this->nama_tayang)),
            'tanggal_tayang' => htmlspecialchars(strip_tags($this->tanggal_tayang)),
            'jam_mulai' => htmlspecialchars(strip_tags($this->jam_mulai)),
            'jam_selesai' => htmlspecialchars(strip_tags($this->jam_selesai)),
            'harga_tiket' => htmlspecialchars(strip_tags($this->harga_tiket))
        ];

        return $this->qb->reset()->table($this->table_name)->insert($data);
    }

    // READ ALL WITH DETAILS
    public function readAll() {
        $stmt = $this->qb->reset()
            ->table($this->table_name . ' jt')
            ->select('jt.*, f.judul_film, b.nama_bioskop, b.kota')
            ->leftJoin('Film f', 'jt.id_film', '=', 'f.id_film')
            ->leftJoin('Bioskop b', 'jt.id_bioskop', '=', 'b.id_bioskop')
            ->orderBy('jt.tanggal_tayang', 'DESC')
            ->get();
        
        return $stmt;
    }

    // READ BY DATE
    public function readByDate($tanggal) {
        $stmt = $this->qb->reset()
            ->table($this->table_name . ' jt')
            ->select('jt.*, f.judul_film, b.nama_bioskop, b.kota')
            ->leftJoin('Film f', 'jt.id_film', '=', 'f.id_film')
            ->leftJoin('Bioskop b', 'jt.id_bioskop', '=', 'b.id_bioskop')
            ->where('jt.tanggal_tayang', '=', $tanggal)
            ->orderBy('jt.jam_mulai', 'ASC')
            ->get();
        
        return $stmt;
    }

    // READ BY FILM
    public function readByFilm($id_film) {
        $stmt = $this->qb->reset()
            ->table($this->table_name . ' jt')
            ->select('jt.*, b.nama_bioskop, b.kota')
            ->leftJoin('Bioskop b', 'jt.id_bioskop', '=', 'b.id_bioskop')
            ->where('jt.id_film', '=', $id_film)
            ->orderBy('jt.tanggal_tayang', 'ASC')
            ->get();
        
        return $stmt;
    }

    // READ BY BIOSKOP
    public function readByBioskop($id_bioskop) {
        $stmt = $this->qb->reset()
            ->table($this->table_name . ' jt')
            ->select('jt.*, f.judul_film')
            ->leftJoin('Film f', 'jt.id_film', '=', 'f.id_film')
            ->where('jt.id_bioskop', '=', $id_bioskop)
            ->orderBy('jt.tanggal_tayang', 'ASC')
            ->get();
        
        return $stmt;
    }

    // READ ONE
    public function readOne() {
        $row = $this->qb->reset()
            ->table($this->table_name . ' jt')
            ->select('jt.*, f.judul_film, b.nama_bioskop')
            ->leftJoin('Film f', 'jt.id_film', '=', 'f.id_film')
            ->leftJoin('Bioskop b', 'jt.id_bioskop', '=', 'b.id_bioskop')
            ->where('jt.id_tayang', '=', $this->id_tayang)
            ->first();

        if ($row) {
            $this->id_film = $row['id_film'];
            $this->id_bioskop = $row['id_bioskop'];
            $this->nama_tayang = $row['nama_tayang'];
            $this->tanggal_tayang = $row['tanggal_tayang'];
            $this->jam_mulai = $row['jam_mulai'];
            $this->jam_selesai = $row['jam_selesai'];
            $this->harga_tiket = $row['harga_tiket'];
            return true;
        }
        
        return false;
    }

    // UPDATE
    public function update() {
        $data = [
            'id_film' => htmlspecialchars(strip_tags($this->id_film)),
            'id_bioskop' => htmlspecialchars(strip_tags($this->id_bioskop)),
            'nama_tayang' => htmlspecialchars(strip_tags($this->nama_tayang)),
            'tanggal_tayang' => htmlspecialchars(strip_tags($this->tanggal_tayang)),
            'jam_mulai' => htmlspecialchars(strip_tags($this->jam_mulai)),
            'jam_selesai' => htmlspecialchars(strip_tags($this->jam_selesai)),
            'harga_tiket' => htmlspecialchars(strip_tags($this->harga_tiket))
        ];

        return $this->qb->reset()
            ->table($this->table_name)
            ->where('id_tayang', '=', htmlspecialchars(strip_tags($this->id_tayang)))
            ->update($data);
    }

    // DELETE
    public function delete() {
        return $this->qb->reset()
            ->table($this->table_name)
            ->where('id_tayang', '=', htmlspecialchars(strip_tags($this->id_tayang)))
            ->delete();
    }

    // COUNT TOTAL
    public function countTotal() {
        return $this->qb->reset()
            ->table($this->table_name)
            ->count();
    }

    // GET TODAY SCHEDULES
    public function getTodaySchedules() {
        $today = date('Y-m-d');
        return $this->readByDate($today);
    }
}
?>