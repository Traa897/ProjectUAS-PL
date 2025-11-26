<?php
// controllers/AdminController.php
require_once 'config/database.php';
require_once 'models/Film.php';
require_once 'models/Transaksi.php';
require_once 'models/QueryBuilder.php';

class AdminController {
    private $db;
    private $film;
    private $transaksi;
    private $qb;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->film = new Film($this->db);
        $this->transaksi = new Transaksi($this->db);
        $this->qb = new QueryBuilder($this->db);
        
        if(session_status() == PHP_SESSION_NONE) session_start();
        
        // Check if user is admin
        if(!isset($_SESSION['admin_id'])) {
            header("Location: index.php?module=auth&action=index");
            exit();
        }
    }

    // Dashboard Admin - Simpel
    public function dashboard() {
        // Get statistics
        $totalFilms = $this->qb->reset()->table('Film')->count();
        $totalBioskops = $this->qb->reset()->table('Bioskop')->count();
        $totalJadwals = $this->qb->reset()->table('Jadwal_Tayang')->count();
        $totalUsers = $this->qb->reset()->table('User')->count();
        
        // Get transactions statistics
        $totalTransaksi = $this->transaksi->countTotal();
        $transaksiSuccess = $this->transaksi->countByStatus('berhasil');
        $totalRevenue = $this->transaksi->getTotalRevenue();
        
        // Get recent transactions (last 10)
        $stmt = $this->transaksi->readAll();
        $recentTransactions = array_slice($stmt->fetchAll(PDO::FETCH_ASSOC), 0, 10);
        
        require_once 'views/admin/dashboard.php';
    }

    // Laporan & Transaksi - Combined View
    public function laporanTransaksi() {
        // Filter parameters
        $dateFrom = isset($_GET['date_from']) ? $_GET['date_from'] : date('Y-m-01');
        $dateTo = isset($_GET['date_to']) ? $_GET['date_to'] : date('Y-m-d');
        $status = isset($_GET['status']) ? $_GET['status'] : '';
        
        // Get filtered transactions
        $query = "SELECT t.*, u.nama_lengkap as nama_user, u.email
                  FROM Transaksi t
                  JOIN User u ON t.id_user = u.id_user
                  WHERE DATE(t.tanggal_transaksi) BETWEEN :date_from AND :date_to";
        
        if($status != '') {
            $query .= " AND t.status_pembayaran = :status";
        }
        
        $query .= " ORDER BY t.tanggal_transaksi DESC";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':date_from', $dateFrom);
        $stmt->bindParam(':date_to', $dateTo);
        if($status != '') {
            $stmt->bindParam(':status', $status);
        }
        $stmt->execute();
        $transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Calculate summary
        $totalTransaksi = count($transactions);
        $totalPendapatan = array_sum(array_column($transactions, 'total_harga'));
        $totalTiket = array_sum(array_column($transactions, 'jumlah_tiket'));
        
        require_once 'views/admin/laporan_transaksi.php';
    }

    // Kelola Film
    public function kelolaFilm() {
        $stmt = $this->film->readAll();
        $films = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        require_once 'views/admin/kelola_film.php';
    }

    // Detail Transaksi
    public function detailTransaksi() {
        if(!isset($_GET['id'])) {
            header("Location: index.php?module=admin&action=laporanTransaksi");
            exit();
        }

        $detailTransaksi = $this->transaksi->getDetailWithTickets($_GET['id']);
        
        if(!$detailTransaksi) {
            header("Location: index.php?module=admin&action=laporanTransaksi");
            exit();
        }

        require_once 'views/admin/detail_transaksi.php';
    }

    // Update Status Transaksi
    public function updateStatus() {
        if($_SERVER['REQUEST_METHOD'] != 'POST') {
            header("Location: index.php?module=admin&action=laporanTransaksi");
            exit();
        }

        $id_transaksi = $_POST['id_transaksi'];
        $status = $_POST['status'];

        $this->transaksi->id_transaksi = $id_transaksi;
        
        if($this->transaksi->updateStatusPembayaran($status)) {
            $_SESSION['flash'] = 'Status pembayaran berhasil diupdate!';
        } else {
            $_SESSION['flash'] = 'Gagal update status!';
        }

        header("Location: index.php?module=admin&action=laporanTransaksi");
        exit();
    }
}
?>