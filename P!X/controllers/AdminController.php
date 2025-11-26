<?php
// controllers/AdminController.php - FIXED VERSION
require_once 'config/database.php';
require_once 'models/Film.php';
require_once 'models/Transaksi.php';
require_once 'models/QueryBuilder.php';
require_once 'models/Genre.php';

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

    // Dashboard Admin - Gabungan dengan Laporan
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
        
        // Get top selling films
        $query = "SELECT f.id_film, f.judul_film, f.poster_url, 
                         COUNT(DISTINCT t.id_transaksi) as total_transaksi,
                         SUM(t.jumlah_tiket) as total_tiket,
                         SUM(t.total_harga) as total_pendapatan
                  FROM Film f
                  LEFT JOIN Jadwal_Tayang jt ON f.id_film = jt.id_film
                  LEFT JOIN Detail_Transaksi dt ON jt.id_tayang = dt.id_jadwal_tayang
                  LEFT JOIN Transaksi t ON dt.id_transaksi = t.id_transaksi AND t.status_pembayaran = 'berhasil'
                  GROUP BY f.id_film, f.judul_film, f.poster_url
                  ORDER BY total_tiket DESC
                  LIMIT 5";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $topFilms = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Get monthly revenue (6 months)
        $query = "SELECT 
                    DATE_FORMAT(tanggal_transaksi, '%Y-%m') as bulan,
                    COUNT(*) as jumlah_transaksi,
                    SUM(total_harga) as pendapatan
                  FROM Transaksi
                  WHERE status_pembayaran = 'berhasil'
                    AND tanggal_transaksi >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
                  GROUP BY DATE_FORMAT(tanggal_transaksi, '%Y-%m')
                  ORDER BY bulan DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $monthlyRevenue = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Get all films for management
        $stmt = $this->film->readAll();
        $films = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        require_once 'views/admin/dashboard.php';
    }

    // Detail Transaksi
    public function detailTransaksi() {
        if(!isset($_GET['id'])) {
            header("Location: index.php?module=admin&action=dashboard");
            exit();
        }

        $detailTransaksi = $this->transaksi->getDetailWithTickets($_GET['id']);
        
        if(!$detailTransaksi) {
            header("Location: index.php?module=admin&action=dashboard");
            exit();
        }

        require_once 'views/admin/detail_transaksi.php';
    }

    // Update Status Transaksi
    public function updateStatus() {
        if($_SERVER['REQUEST_METHOD'] != 'POST') {
            header("Location: index.php?module=admin&action=dashboard");
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

        header("Location: index.php?module=admin&action=dashboard");
        exit();
    }
    
    // CREATE Film - Show form
    public function createFilm() {
        $genre = new Genre($this->db);
        $genres = $genre->readAll()->fetchAll(PDO::FETCH_ASSOC);
        
        require_once 'views/admin/create_film.php';
    }
    
    // STORE Film - Save new film
    public function storeFilm() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->film->judul_film = $_POST['judul_film'];
            $this->film->tahun_rilis = $_POST['tahun_rilis'];
            $this->film->durasi_menit = $_POST['durasi_menit'];
            $this->film->sipnosis = $_POST['sipnosis'];
            $this->film->rating = $_POST['rating'];
            $this->film->poster_url = $_POST['poster_url'];
            $this->film->id_genre = $_POST['id_genre'];

            if($this->film->create()) {
                $_SESSION['flash'] = 'Film berhasil ditambahkan!';
                header("Location: index.php?module=admin&action=dashboard");
                exit();
            } else {
                $_SESSION['flash'] = 'Gagal menambahkan film!';
                header("Location: index.php?module=admin&action=createFilm");
                exit();
            }
        }
    }
    
    // EDIT Film - Show edit form
    public function editFilm() {
        if(!isset($_GET['id'])) {
            header("Location: index.php?module=admin&action=dashboard");
            exit();
        }
        
        $genre = new Genre($this->db);
        
        $this->film->id_film = $_GET['id'];
        if($this->film->readOne()) {
            $genres = $genre->readAll()->fetchAll(PDO::FETCH_ASSOC);
            require_once 'views/admin/edit_film.php';
        } else {
            $_SESSION['flash'] = 'Film tidak ditemukan!';
            header("Location: index.php?module=admin&action=dashboard");
            exit();
        }
    }
    
    // UPDATE Film
    public function updateFilm() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->film->id_film = $_POST['id_film'];
            $this->film->judul_film = $_POST['judul_film'];
            $this->film->tahun_rilis = $_POST['tahun_rilis'];
            $this->film->durasi_menit = $_POST['durasi_menit'];
            $this->film->sipnosis = $_POST['sipnosis'];
            $this->film->rating = $_POST['rating'];
            $this->film->poster_url = $_POST['poster_url'];
            $this->film->id_genre = $_POST['id_genre'];

            if($this->film->update()) {
                $_SESSION['flash'] = 'Film berhasil diupdate!';
                header("Location: index.php?module=admin&action=dashboard");
                exit();
            } else {
                $_SESSION['flash'] = 'Gagal mengupdate film!';
                header("Location: index.php?module=admin&action=editFilm&id=" . $this->film->id_film);
                exit();
            }
        }
    }
    
    // DELETE Film
    public function deleteFilm() {
        if(!isset($_GET['id'])) {
            header("Location: index.php?module=admin&action=dashboard");
            exit();
        }
        
        $this->film->id_film = $_GET['id'];
        if($this->film->delete()) {
            $_SESSION['flash'] = 'Film berhasil dihapus!';
        } else {
            $_SESSION['flash'] = 'Gagal menghapus film!';
        }
        
        header("Location: index.php?module=admin&action=dashboard");
        exit();
    }
}
?>