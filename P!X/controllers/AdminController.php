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
        if(!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
            header('Location: index.php?module=film');
            exit();
        }
    }

    // Admin Dashboard
    public function dashboard() {
        // Statistics
        $totalFilms = $this->qb->reset()->table('Film')->count();
        $totalBioskops = $this->qb->reset()->table('Bioskop')->count();
        $totalJadwals = $this->qb->reset()->table('Jadwal_Tayang')->count();
        $totalUsers = $this->qb->reset()->table('User')->where('status_akun', '=', 'aktif')->count();
        $totalTransaksi = $this->transaksi->countTotal();
        $totalRevenue = $this->transaksi->getTotalRevenue();
        
        // Top Selling Films
        $query = "SELECT f.judul_film, f.poster_url, COUNT(dt.id_detail) as total_tiket, 
                  SUM(dt.harga_tiket) as total_pendapatan
                  FROM Film f
                  LEFT JOIN Jadwal_Tayang jt ON f.id_film = jt.id_film
                  LEFT JOIN Detail_Transaksi dt ON jt.id_tayang = dt.id_jadwal_tayang
                  LEFT JOIN Transaksi t ON dt.id_transaksi = t.id_transaksi
                  WHERE t.status_pembayaran = 'berhasil'
                  GROUP BY f.id_film, f.judul_film, f.poster_url
                  ORDER BY total_tiket DESC
                  LIMIT 10";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $topFilms = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Recent Transactions
        $recentTransactions = $this->qb->reset()
            ->table('Transaksi t')
            ->select('t.*, u.nama_lengkap, u.email')
            ->leftJoin('User u', 't.id_user', '=', 'u.id_user')
            ->orderBy('t.tanggal_transaksi', 'DESC')
            ->limit(10)
            ->get()
            ->fetchAll(PDO::FETCH_ASSOC);
        
        // Monthly Revenue
        $query = "SELECT DATE_FORMAT(tanggal_transaksi, '%Y-%m') as bulan,
                  SUM(total_harga) as pendapatan,
                  COUNT(*) as jumlah_transaksi
                  FROM Transaksi
                  WHERE status_pembayaran = 'berhasil'
                  AND tanggal_transaksi >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
                  GROUP BY bulan
                  ORDER BY bulan DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $monthlyRevenue = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        require_once 'views/admin/dashboard.php';
    }

    // Film yang Terjual
    public function laporan() {
        $filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';
        $startDate = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-01');
        $endDate = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-d');
        
        // Query untuk film terjual
        $query = "SELECT f.id_film, f.judul_film, f.poster_url, f.tahun_rilis,
                  COUNT(DISTINCT t.id_transaksi) as total_transaksi,
                  COUNT(dt.id_detail) as total_tiket,
                  SUM(dt.harga_tiket) as total_pendapatan,
                  AVG(dt.harga_tiket) as rata_rata_harga
                  FROM Film f
                  LEFT JOIN Jadwal_Tayang jt ON f.id_film = jt.id_film
                  LEFT JOIN Detail_Transaksi dt ON jt.id_tayang = dt.id_jadwal_tayang
                  LEFT JOIN Transaksi t ON dt.id_transaksi = t.id_transaksi
                  WHERE t.status_pembayaran = 'berhasil'";
        
        if($filter !== 'all') {
            $query .= " AND DATE(t.tanggal_transaksi) BETWEEN :start_date AND :end_date";
        }
        
        $query .= " GROUP BY f.id_film, f.judul_film, f.poster_url, f.tahun_rilis
                    ORDER BY total_tiket DESC";
        
        $stmt = $this->db->prepare($query);
        if($filter !== 'all') {
            $stmt->bindParam(':start_date', $startDate);
            $stmt->bindParam(':end_date', $endDate);
        }
        $stmt->execute();
        $films = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Total Income
        $queryIncome = "SELECT SUM(total_harga) as total_income,
                        COUNT(*) as total_transaksi,
                        SUM(jumlah_tiket) as total_tiket
                        FROM Transaksi
                        WHERE status_pembayaran = 'berhasil'";
        
        if($filter !== 'all') {
            $queryIncome .= " AND DATE(tanggal_transaksi) BETWEEN :start_date AND :end_date";
        }
        
        $stmtIncome = $this->db->prepare($queryIncome);
        if($filter !== 'all') {
            $stmtIncome->bindParam(':start_date', $startDate);
            $stmtIncome->bindParam(':end_date', $endDate);
        }
        $stmtIncome->execute();
        $income = $stmtIncome->fetch(PDO::FETCH_ASSOC);
        
        require_once 'views/admin/laporan.php';
    }

    // Detail Penjualan per Film
    public function detailPenjualan() {
        if(!isset($_GET['id_film'])) {
            header('Location: index.php?module=admin&action=laporan');
            exit();
        }
        
        $id_film = $_GET['id_film'];
        
        // Get film info
        $this->film->id_film = $id_film;
        $this->film->readOne();
        
        // Get sales details
        $query = "SELECT t.kode_booking, t.tanggal_transaksi, t.jumlah_tiket, t.total_harga,
                  u.nama_lengkap, u.email, b.nama_bioskop, jt.tanggal_tayang,
                  GROUP_CONCAT(dt.nomor_kursi ORDER BY dt.nomor_kursi SEPARATOR ', ') as kursi
                  FROM Transaksi t
                  JOIN User u ON t.id_user = u.id_user
                  JOIN Detail_Transaksi dt ON t.id_transaksi = dt.id_transaksi
                  JOIN Jadwal_Tayang jt ON dt.id_jadwal_tayang = jt.id_tayang
                  JOIN Bioskop b ON jt.id_bioskop = b.id_bioskop
                  WHERE jt.id_film = :id_film AND t.status_pembayaran = 'berhasil'
                  GROUP BY t.id_transaksi
                  ORDER BY t.tanggal_transaksi DESC";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id_film', $id_film);
        $stmt->execute();
        $sales = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Statistics
        $totalTiket = 0;
        $totalPendapatan = 0;
        foreach($sales as $sale) {
            $totalTiket += $sale['jumlah_tiket'];
            $totalPendapatan += $sale['total_harga'];
        }
        
        require_once 'views/admin/detail_penjualan.php';
    }
}
?>