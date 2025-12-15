<?php
// models/Bioskop.php - REFACTORED dengan OOP
require_once 'models/BaseModel.php';

class Bioskop extends BaseModel {
    use Searchable;
    
    public $id_bioskop;
    public $nama_bioskop;
    public $kota;
    public $alamat_bioskop;
    public $jumlah_studio;
    public $logo_url;

    protected function getTableName() {
        return "Bioskop";
    }
    
    protected function getPrimaryKey() {
        return "id_bioskop";
    }
    
    protected function getSearchableFields() {
        return ['nama_bioskop', 'kota', 'alamat_bioskop'];
    }
    
    protected function prepareData() {
        return [
            'nama_bioskop' => $this->sanitize($this->nama_bioskop),
            'kota' => $this->sanitize($this->kota),
            'alamat_bioskop' => $this->sanitize($this->alamat_bioskop),
            'jumlah_studio' => $this->sanitize($this->jumlah_studio),
            'logo_url' => $this->sanitize($this->logo_url)
        ];
    }
    
    // Polymorphism - Override readOne untuk memastikan compatibility
    public function readOne() {
        $row = $this->qb->reset()
            ->table($this->getTableName())
            ->select('*')
            ->where($this->getPrimaryKey(), '=', $this->id_bioskop)
            ->first();

        if ($row) {
            $this->populateFromArray($row);
            return true;
        }
        
        return false;
    }
    
    public function readByCity($kota) {
        $stmt = $this->qb->reset()
            ->table($this->getTableName())
            ->where('kota', '=', $kota)
            ->orderBy('nama_bioskop', 'ASC')
            ->get();
        
        return $stmt;
    }
    
    public function getBioskopWithSchedules($id_bioskop) {
        $stmt = $this->qb->reset()
            ->table($this->getTableName() . ' b')
            ->select('b.*, COUNT(jt.id_tayang) as total_jadwal')
            ->leftJoin('Jadwal_Tayang jt', 'b.id_bioskop', '=', 'jt.id_bioskop')
            ->where('b.id_bioskop', '=', $id_bioskop)
            ->groupBy('b.id_bioskop')
            ->first();
        
        return $stmt;
    }
}
?>