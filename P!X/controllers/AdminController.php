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
            $_SESSION['flash'] = 'Anda harus login sebagai admin!';
            header("Location: index.php?module=auth&action=index");
            exit();
        }
    }

    // DEFAULT INDEX - Redirect to Dashboard
    public function index() {
        $this->dashboard();
    }

    // Dashboard Admin - FIXED
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
        
        // Get recent transactions (last 10) - FIXED with LEFT JOIN
        $query = "SELECT t.*, 
                         u.nama_lengkap as nama_user,
                         u.email,
                         u.no_telpon
                  FROM Transaksi t
                  LEFT JOIN User u ON t.id_user = u.id_user
                  ORDER BY t.tanggal_transaksi DESC
                  LIMIT 10";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $recentTransactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Get top selling films
        $query = "SELECT 
                    f.id_film, 
                    f.judul_film, 
                    f.poster_url, 
                    COUNT(DISTINCT t.id_transaksi) as total_transaksi,
                    COALESCE(SUM(t.jumlah_tiket), 0) as total_tiket,
                    COALESCE(SUM(t.total_harga), 0) as total_pendapatan
                  FROM Film f
                  LEFT JOIN Jadwal_Tayang jt ON f.id_film = jt.id_film
                  LEFT JOIN Detail_Transaksi dt ON jt.id_tayang = dt.id_jadwal_tayang
                  LEFT JOIN Transaksi t ON dt.id_transaksi = t.id_transaksi 
                    AND t.status_pembayaran = 'berhasil'
                  GROUP BY f.id_film, f.judul_film, f.poster_url
                  HAVING total_tiket > 0
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
    
    // USER MANAGEMENT
    public function kelolaUser() {
        $search = isset($_GET['search']) ? $_GET['search'] : '';
        
        $query = "SELECT 
                    u.*,
                    COUNT(DISTINCT t.id_transaksi) as total_transaksi,
                    COALESCE(SUM(CASE WHEN t.status_pembayaran = 'berhasil' THEN t.total_harga ELSE 0 END), 0) as total_belanja
                  FROM User u
                  LEFT JOIN Transaksi t ON u.id_user = t.id_user
                  WHERE 1=1";
        
        if($search != '') {
            $query .= " AND (u.nama_lengkap LIKE :search OR u.email LIKE :search OR u.username LIKE :search)";
        }
        
        $query .= " GROUP BY u.id_user
                    ORDER BY total_belanja DESC, u.tanggal_daftar DESC";
        
        $stmt = $this->db->prepare($query);
        
        if($search != '') {
            $searchParam = "%$search%";
            $stmt->bindParam(':search', $searchParam);
        }
        
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $activeUsers = 0;
        $totalBookings = 0;
        $totalRevenue = 0;
        
        foreach($users as $user) {
            if($user['status_akun'] === 'aktif') $activeUsers++;
            $totalBookings += $user['total_transaksi'];
            $totalRevenue += $user['total_belanja'];
        }
        
        require_once 'views/admin/kelola_user.php';
    }
    
    // Detail User
    public function detailUser() {
        if(!isset($_GET['id'])) {
            header("Location: index.php?module=admin&action=kelolaUser");
            exit();
        }
        
        $id_user = $_GET['id'];
        
        $query = "SELECT * FROM User WHERE id_user = :id_user";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id_user', $id_user);
        $stmt->execute();
        $userData = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if(!$userData) {
            $_SESSION['flash'] = 'User tidak ditemukan!';
            header("Location: index.php?module=admin&action=kelolaUser");
            exit();
        }
        
        $stmt = $this->transaksi->readByUser($id_user);
        $transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $totalTransaksi = count($transactions);
        $transaksiSuccess = 0;
        $totalBelanja = 0;
        
        foreach($transactions as $trans) {
            if($trans['status_pembayaran'] === 'berhasil') {
                $transaksiSuccess++;
                $totalBelanja += $trans['total_harga'];
            }
        }
        
        require_once 'views/admin/detail_user.php';
    }
    
    // Toggle User Status
    public function toggleUserStatus() {
        if(!isset($_GET['id']) || !isset($_GET['status'])) {
            header("Location: index.php?module=admin&action=kelolaUser");
            exit();
        }
        
        $id_user = $_GET['id'];
        $status = $_GET['status'];
        
        $query = "UPDATE User SET status_akun = :status WHERE id_user = :id_user";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id_user', $id_user);
        
        if($stmt->execute()) {
            $_SESSION['flash'] = 'Status user berhasil diubah!';
        } else {
            $_SESSION['flash'] = 'Gagal mengubah status user!';
        }
        
        header("Location: index.php?module=admin&action=kelolaUser");
        exit();
    }
}
?>