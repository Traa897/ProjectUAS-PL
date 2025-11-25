<?php
// models/DetailTransaksi.php
require_once 'models/QueryBuilder.php';

class DetailTransaksi {
    private $conn;
    private $qb;
    private $table_name = "Detail_Transaksi";

    public $id_detail;
    public $id_transaksi;
    public $id_jadwal_tayang;
    public $nomor_kursi;
    public $harga_tiket;
    public $jenis_tiket;

    public function __construct($db) {
        $this->conn = $db;
        $this->qb = new QueryBuilder($db);
    }

    // CREATE Detail Transaksi
    public function create() {
        $data = [
            'id_transaksi' => htmlspecialchars(strip_tags($this->id_transaksi)),
            'id_jadwal_tayang' => htmlspecialchars(strip_tags($this->id_jadwal_tayang)),
            'nomor_kursi' => htmlspecialchars(strip_tags($this->nomor_kursi)),
            'harga_tiket' => htmlspecialchars(strip_tags($this->harga_tiket)),
            'jenis_tiket' => htmlspecialchars(strip_tags($this->jenis_tiket))
        ];

        return $this->qb->reset()->table($this->table_name)->insert($data);
    }

    // Get Detail by Transaksi
    public function getByTransaksi($id_transaksi) {
        $stmt = $this->qb->reset()
            ->table($this->table_name . ' dt')
            ->select('dt.*, jt.tanggal_tayang, jt.jam_mulai, jt.jam_selesai, f.judul_film, b.nama_bioskop')
            ->leftJoin('Jadwal_Tayang jt', 'dt.id_jadwal_tayang', '=', 'jt.id_tayang')
            ->leftJoin('Film f', 'jt.id_film', '=', 'f.id_film')
            ->leftJoin('Bioskop b', 'jt.id_bioskop', '=', 'b.id_bioskop')
            ->where('dt.id_transaksi', '=', $id_transaksi)
            ->get();
        
        return $stmt;
    }

    // Get Kursi Terpesan by Jadwal
    public function getKursiTerpesanByJadwal($id_jadwal_tayang) {
        $stmt = $this->qb->reset()
            ->table($this->table_name . ' dt')
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

    // Check if Kursi Available
    public function isKursiAvailable($id_jadwal_tayang, $nomor_kursi) {
        $count = $this->qb->reset()
            ->table($this->table_name . ' dt')
            ->leftJoin('Transaksi t', 'dt.id_transaksi', '=', 't.id_transaksi')
            ->where('dt.id_jadwal_tayang', '=', $id_jadwal_tayang)
            ->where('dt.nomor_kursi', '=', $nomor_kursi)
            ->where('t.status_pembayaran', '=', 'berhasil')
            ->count();
        
        return $count === 0;
    }

    // DELETE Detail
    public function delete() {
        return $this->qb->reset()
            ->table($this->table_name)
            ->where('id_detail', '=', htmlspecialchars(strip_tags($this->id_detail)))
            ->delete();
    }

    // Generate Random Available Kursi
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
                return null; // Tidak ada kursi tersedia
            }
        } while (in_array($kursi, $kursi_terpesan));
        
        return $kursi;
    }
}
?>