<?php
// models/Transaksi.php
require_once 'models/QueryBuilder.php';

class Transaksi {
    private $conn;
    private $qb;
    private $table_name = "Transaksi";

    public $id_transaksi;
    public $id_user;
    public $id_admin;
    public $kode_booking;
    public $tanggal_transaksi;
    public $jumlah_tiket;
    public $total_harga;
    public $metode_pembayaran;
    public $status_pembayaran;
    public $tanggal_pembayaran;

    public function __construct($db) {
        $this->conn = $db;
        $this->qb = new QueryBuilder($db);
    }

    // CREATE Transaksi
    public function create() {
        // Generate kode booking
        $this->kode_booking = 'BKG' . date('YmdHis') . rand(100, 999);
        
        $data = [
            'id_user' => htmlspecialchars(strip_tags($this->id_user)),
            'id_admin' => $this->id_admin ? htmlspecialchars(strip_tags($this->id_admin)) : null,
            'kode_booking' => $this->kode_booking,
            'jumlah_tiket' => htmlspecialchars(strip_tags($this->jumlah_tiket)),
            'total_harga' => htmlspecialchars(strip_tags($this->total_harga)),
            'metode_pembayaran' => htmlspecialchars(strip_tags($this->metode_pembayaran)),
            'status_pembayaran' => 'berhasil', // Langsung berhasil untuk demo
            'tanggal_pembayaran' => date('Y-m-d H:i:s')
        ];

        if($this->qb->reset()->table($this->table_name)->insert($data)) {
            // Get last insert ID
            $this->id_transaksi = $this->conn->lastInsertId();
            return true;
        }
        
        return false;
    }

    // READ ALL Transaksi
    public function readAll() {
        $stmt = $this->qb->reset()
            ->table($this->table_name . ' t')
            ->select('t.*, u.nama_lengkap as nama_user, u.email, a.nama_lengkap as nama_admin')
            ->leftJoin('User u', 't.id_user', '=', 'u.id_user')
            ->leftJoin('Admin a', 't.id_admin', '=', 'a.id_admin')
            ->orderBy('t.tanggal_transaksi', 'DESC')
            ->get();
        
        return $stmt;
    }

    // READ BY USER
    public function readByUser($id_user) {
        $stmt = $this->qb->reset()
            ->table($this->table_name . ' t')
            ->select('t.*, a.nama_lengkap as nama_admin')
            ->leftJoin('Admin a', 't.id_admin', '=', 'a.id_admin')
            ->where('t.id_user', '=', $id_user)
            ->orderBy('t.tanggal_transaksi', 'DESC')
            ->get();
        
        return $stmt;
    }

    // READ ONE
    public function readOne() {
        $row = $this->qb->reset()
            ->table($this->table_name . ' t')
            ->select('t.*, u.nama_lengkap as nama_user, u.email, u.no_telpon')
            ->leftJoin('User u', 't.id_user', '=', 'u.id_user')
            ->where('t.id_transaksi', '=', $this->id_transaksi)
            ->first();
        
        return $row;
    }

    // GET BY KODE BOOKING
    public function getByKodeBooking($kode_booking) {
        $row = $this->qb->reset()
            ->table($this->table_name)
            ->select('*')
            ->where('kode_booking', '=', $kode_booking)
            ->first();
        
        return $row;
    }

    // GET DETAIL WITH TICKETS
    public function getDetailWithTickets($id_transaksi) {
        // Get transaksi info
        $this->id_transaksi = $id_transaksi;
        $transaksi = $this->readOne();
        
        if(!$transaksi) return null;
        
        // Get detail tickets
        $query = "SELECT dt.*, jt.tanggal_tayang, jt.jam_mulai, jt.jam_selesai, 
                         f.judul_film, f.durasi_menit, b.nama_bioskop, b.alamat_bioskop, b.kota
                  FROM Detail_Transaksi dt
                  JOIN Jadwal_Tayang jt ON dt.id_jadwal_tayang = jt.id_tayang
                  JOIN Film f ON jt.id_film = f.id_film
                  JOIN Bioskop b ON jt.id_bioskop = b.id_bioskop
                  WHERE dt.id_transaksi = :id_transaksi
                  ORDER BY dt.nomor_kursi ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_transaksi', $id_transaksi);
        $stmt->execute();
        $tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return [
            'transaksi' => $transaksi,
            'tickets' => $tickets
        ];
    }

    // UPDATE STATUS PEMBAYARAN
    public function updateStatusPembayaran($status) {
        $data = [
            'status_pembayaran' => $status,
            'tanggal_pembayaran' => ($status == 'berhasil') ? date('Y-m-d H:i:s') : null
        ];

        return $this->qb->reset()
            ->table($this->table_name)
            ->where('id_transaksi', '=', $this->id_transaksi)
            ->update($data);
    }

    // DELETE
    public function delete() {
        return $this->qb->reset()
            ->table($this->table_name)
            ->where('id_transaksi', '=', htmlspecialchars(strip_tags($this->id_transaksi)))
            ->delete();
    }

    // COUNT TOTAL
    public function countTotal() {
        return $this->qb->reset()
            ->table($this->table_name)
            ->count();
    }

    // COUNT BY STATUS
    public function countByStatus($status) {
        return $this->qb->reset()
            ->table($this->table_name)
            ->where('status_pembayaran', '=', $status)
            ->count();
    }

    // GET TOTAL REVENUE
    public function getTotalRevenue() {
        $query = "SELECT SUM(total_harga) as total FROM " . $this->table_name . " 
                  WHERE status_pembayaran = 'berhasil'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $row['total'] ?? 0;
    }
}
?>