<?php
require_once 'models/BaseModel.php';

class DetailTransaksi extends BaseModel {
    public $id_detail;
    public $id_transaksi;
    public $id_jadwal_tayang;
    public $nomor_kursi;
    public $harga_tiket;
    public $jenis_tiket;

    protected function getTableName() {
        return "Detail_Transaksi";
    }
    
    protected function getPrimaryKey() {
        return "id_detail";
    }
    
    protected function prepareData() {
        return [
            'id_transaksi' => $this->sanitize($this->id_transaksi),
            'id_jadwal_tayang' => $this->sanitize($this->id_jadwal_tayang),
            'nomor_kursi' => $this->sanitize($this->nomor_kursi),
            'harga_tiket' => $this->sanitize($this->harga_tiket),
            'jenis_tiket' => $this->sanitize($this->jenis_tiket)
        ];
    }

    public function getByTransaksi($id_transaksi) {
        $stmt = $this->qb->reset()
            ->table($this->getTableName() . ' dt')
            ->select('dt.*, jt.tanggal_tayang, jt.jam_mulai, jt.jam_selesai, f.judul_film, b.nama_bioskop')
            ->leftJoin('Jadwal_Tayang jt', 'dt.id_jadwal_tayang', '=', 'jt.id_tayang')
            ->leftJoin('Film f', 'jt.id_film', '=', 'f.id_film')
            ->leftJoin('Bioskop b', 'jt.id_bioskop', '=', 'b.id_bioskop')
            ->where('dt.id_transaksi', '=', $id_transaksi)
            ->get();
        
        return $stmt;
    }

    public function getKursiTerpesanByJadwal($id_jadwal_tayang) {
        $stmt = $this->qb->reset()
            ->table($this->getTableName() . ' dt')
            ->select('dt.nomor_kursi')
            ->leftJoin('Transaksi t', 'dt.id_transaksi', '=', 't.id_transaksi')
            ->where('dt.id_jadwal_tayang', '=', $id_jadwal_tayang)
            ->where('t.status_pembayaran', '=', 'berhasil')
            ->get();
        
        $kursi = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $kursi[] = $row['nomor_kursi'];
        }
        
        return $kursi;
    }

    public function isKursiAvailable($id_jadwal_tayang, $nomor_kursi) {
        $count = $this->qb->reset()
            ->table($this->getTableName() . ' dt')
            ->leftJoin('Transaksi t', 'dt.id_transaksi', '=', 't.id_transaksi')
            ->where('dt.id_jadwal_tayang', '=', $id_jadwal_tayang)
            ->where('dt.nomor_kursi', '=', $nomor_kursi)
            ->where('t.status_pembayaran', '=', 'berhasil')
            ->count();
        
        return $count === 0;
    }

    public function generateRandomKursi($id_jadwal_tayang) {
        $kursi_terpesan = $this->getKursiTerpesanByJadwal($id_jadwal_tayang);
        $baris = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J'];
        $max_attempts = 100;
        $attempts = 0;
        
        do {
            $random_baris = $baris[array_rand($baris)];
            $random_nomor = rand(1, 10);
            $kursi = $random_baris . $random_nomor;
            $attempts++;
            
            if ($attempts > $max_attempts) {
                return null;
            }
        } while (in_array($kursi, $kursi_terpesan));
        
        return $kursi;
    }
}
?>