<?php
// models/Jadwal.php 
require_once 'models/BaseModel.php';

class Jadwal extends BaseModel {
    public $id_tayang;
    public $id_film;
    public $id_bioskop;
    public $nama_tayang;
    public $tanggal_tayang;
    public $jam_mulai;
    public $jam_selesai;
    public $harga_tiket;

    protected function getTableName() {
        return "Jadwal_Tayang";
    }
    
    protected function getPrimaryKey() {
        return "id_tayang";
    }
    
    protected function prepareData() {
        return [
            'id_film' => $this->sanitize($this->id_film),
            'id_bioskop' => $this->sanitize($this->id_bioskop),
            'nama_tayang' => $this->sanitize($this->nama_tayang),
            'tanggal_tayang' => $this->sanitize($this->tanggal_tayang),
            'jam_mulai' => $this->sanitize($this->jam_mulai),
            'jam_selesai' => $this->sanitize($this->jam_selesai),
            'harga_tiket' => $this->sanitize($this->harga_tiket)
        ];
    }
    
    // Polymorphism - Override readAll dengan JOIN
    public function readAll() {
        $stmt = $this->qb->reset()
            ->table($this->getTableName() . ' jt')
            ->select('jt.*, f.judul_film, b.nama_bioskop, b.kota')
            ->leftJoin('Film f', 'jt.id_film', '=', 'f.id_film')
            ->leftJoin('Bioskop b', 'jt.id_bioskop', '=', 'b.id_bioskop')
            ->orderBy('jt.tanggal_tayang', 'DESC')
            ->get();
        
        return $stmt;
    }
    
    public function readByDate($tanggal) {
        $stmt = $this->qb->reset()
            ->table($this->getTableName() . ' jt')
            ->select('jt.*, f.judul_film, b.nama_bioskop, b.kota')
            ->leftJoin('Film f', 'jt.id_film', '=', 'f.id_film')
            ->leftJoin('Bioskop b', 'jt.id_bioskop', '=', 'b.id_bioskop')
            ->where('jt.tanggal_tayang', '=', $tanggal)
            ->orderBy('jt.jam_mulai', 'ASC')
            ->get();
        
        return $stmt;
    }
    
    public function readByFilm($id_film) {
        $stmt = $this->qb->reset()
            ->table($this->getTableName() . ' jt')
            ->select('jt.*, b.nama_bioskop, b.kota')
            ->leftJoin('Bioskop b', 'jt.id_bioskop', '=', 'b.id_bioskop')
            ->where('jt.id_film', '=', $id_film)
            ->orderBy('jt.tanggal_tayang', 'ASC')
            ->get();
        
        return $stmt;
    }
    
    public function readByBioskop($id_bioskop) {
        $stmt = $this->qb->reset()
            ->table($this->getTableName() . ' jt')
            ->select('jt.*, f.judul_film')
            ->leftJoin('Film f', 'jt.id_film', '=', 'f.id_film')
            ->where('jt.id_bioskop', '=', $id_bioskop)
            ->orderBy('jt.tanggal_tayang', 'ASC')
            ->get();
        
        return $stmt;
    }
    
    // Polymorphism - Override readOne dengan JOIN
    public function readOne() {
        $row = $this->qb->reset()
            ->table($this->getTableName() . ' jt')
            ->select('jt.*, f.judul_film, b.nama_bioskop')
            ->leftJoin('Film f', 'jt.id_film', '=', 'f.id_film')
            ->leftJoin('Bioskop b', 'jt.id_bioskop', '=', 'b.id_bioskop')
            ->where('jt.id_tayang', '=', $this->id_tayang)
            ->first();

        if ($row) {
            $this->populateFromArray($row);
            return true;
        }
        
        return false;
    }
    
    public function getTodaySchedules() {
        $today = date('Y-m-d');
        return $this->readByDate($today);
    }
}
?>