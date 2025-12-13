<?php
// models/Transaksi.php - REFACTORED
require_once 'models/BaseModel.php';

class Transaksi extends BaseModel {
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

    protected function getTableName() {
        return "Transaksi";
    }
    
    protected function getPrimaryKey() {
        return "id_transaksi";
    }
    
    protected function prepareData() {
        return [
            'id_user' => $this->sanitize($this->id_user),
            'id_admin' => $this->id_admin ? $this->sanitize($this->id_admin) : null,
            'jumlah_tiket' => $this->sanitize($this->jumlah_tiket),
            'total_harga' => $this->sanitize($this->total_harga),
            'metode_pembayaran' => $this->sanitize($this->metode_pembayaran)
        ];
    }
    
    // Polymorphism - Override create
    public function create() {
        $this->kode_booking = 'BKG' . date('YmdHis') . rand(100, 999);
        
        $data = $this->prepareData();
        $data['kode_booking'] = $this->kode_booking;
        $data['status_pembayaran'] = 'berhasil';
        $data['tanggal_pembayaran'] = date('Y-m-d H:i:s');

        if($this->qb->reset()->table($this->getTableName())->insert($data)) {
            $this->id_transaksi = $this->conn->lastInsertId();
            return true;
        }
        
        return false;
    }
    
    // Polymorphism - Override readAll dengan JOIN
    public function readAll() {
        $stmt = $this->qb->reset()
            ->table($this->getTableName() . ' t')
            ->select('t.*, u.nama_lengkap as nama_user, u.email, a.nama_lengkap as nama_admin')
            ->leftJoin('User u', 't.id_user', '=', 'u.id_user')
            ->leftJoin('Admin a', 't.id_admin', '=', 'a.id_admin')
            ->orderBy('t.tanggal_transaksi', 'DESC')
            ->get();
        
        return $stmt;
    }

    public function readByUser($id_user) {
        $stmt = $this->qb->reset()
            ->table($this->getTableName() . ' t')
            ->select('t.*, a.nama_lengkap as nama_admin')
            ->leftJoin('Admin a', 't.id_admin', '=', 'a.id_admin')
            ->where('t.id_user', '=', $id_user)
            ->orderBy('t.tanggal_transaksi', 'DESC')
            ->get();
        
        return $stmt;
    }

    // Polymorphism - Override readOne dengan JOIN
    public function readOne() {
        $row = $this->qb->reset()
            ->table($this->getTableName() . ' t')
            ->select('t.*, u.nama_lengkap as nama_user, u.email, u.no_telpon')
            ->leftJoin('User u', 't.id_user', '=', 'u.id_user')
            ->where('t.id_transaksi', '=', $this->id_transaksi)
            ->first();
        
        return $row;
    }

    public function getByKodeBooking($kode_booking) {
        $row = $this->qb->reset()
            ->table($this->getTableName())
            ->select('*')
            ->where('kode_booking', '=', $kode_booking)
            ->first();
        
        return $row;
    }

    public function getDetailWithTickets($id_transaksi) {
        $this->id_transaksi = $id_transaksi;
        $transaksi = $this->readOne();
        
        if(!$transaksi) return null;
        
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

    public function updateStatusPembayaran($status) {
        $data = [
            'status_pembayaran' => $status,
            'tanggal_pembayaran' => ($status == 'berhasil') ? date('Y-m-d H:i:s') : null
        ];

        return $this->qb->reset()
            ->table($this->getTableName())
            ->where($this->getPrimaryKey(), '=', $this->id_transaksi)
            ->update($data);
    }

    public function countByStatus($status) {
        return $this->qb->reset()
            ->table($this->getTableName())
            ->where('status_pembayaran', '=', $status)
            ->count();
    }

    public function getTotalRevenue() {
        $query = "SELECT SUM(total_harga) as total FROM " . $this->getTableName() . " 
                  WHERE status_pembayaran = 'berhasil'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $row['total'] ?? 0;
    }
}