<?php
// controllers/UserController.php
require_once 'config/database.php';
require_once 'models/User.php';
require_once 'models/Transaksi.php';

class UserController {
    private $db;
    private $user;
    private $transaksi;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->user = new User($this->db);
        $this->transaksi = new Transaksi($this->db);
    }

    // Dashboard User
    public function dashboard() {
        if(session_status() == PHP_SESSION_NONE) session_start();
        
        if(!isset($_SESSION['user_id'])) {
            header("Location: index.php?module=auth&action=index");
            exit();
        }

        $this->user->id_user = $_SESSION['user_id'];
        $this->user->readOne();
        
        // Get User Transaction History
        $stmt = $this->transaksi->readByUser($_SESSION['user_id']);
        $transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        require_once 'views/user/dashboard.php';
    }

    // Profile User
    public function profile() {
        if(session_status() == PHP_SESSION_NONE) session_start();
        
        if(!isset($_SESSION['user_id'])) {
            header("Location: index.php?module=auth&action=index");
            exit();
        }

        $this->user->id_user = $_SESSION['user_id'];
        $this->user->readOne();
        
        require_once 'views/user/profile.php';
    }

    // Update Profile
    public function updateProfile() {
        if(session_status() == PHP_SESSION_NONE) session_start();
        
        if(!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] != 'POST') {
            header("Location: index.php?module=user&action=dashboard");
            exit();
        }

        $this->user->id_user = $_SESSION['user_id'];
        $this->user->username = $_POST['username'];
        $this->user->email = $_POST['email'];
        $this->user->nama_lengkap = $_POST['nama_lengkap'];
        $this->user->no_telpon = $_POST['no_telpon'];
        $this->user->tanggal_lahir = $_POST['tanggal_lahir'];
        $this->user->alamat = $_POST['alamat'];

        // Check username & email uniqueness
        if($this->user->usernameExists($_POST['username'], $_SESSION['user_id'])) {
            $_SESSION['flash'] = 'Username sudah digunakan!';
            header("Location: index.php?module=user&action=profile");
            exit();
        }

        if($this->user->emailExists($_POST['email'], $_SESSION['user_id'])) {
            $_SESSION['flash'] = 'Email sudah digunakan!';
            header("Location: index.php?module=user&action=profile");
            exit();
        }

        if($this->user->update()) {
            $_SESSION['user_name'] = $_POST['nama_lengkap'];
            $_SESSION['flash'] = 'Profile berhasil diupdate!';
        } else {
            $_SESSION['flash'] = 'Gagal update profile!';
        }

        header("Location: index.php?module=user&action=profile");
        exit();
    }

    // Riwayat Transaksi
    public function riwayat() {
        if(session_status() == PHP_SESSION_NONE) session_start();
        
        if(!isset($_SESSION['user_id'])) {
            header("Location: index.php?module=auth&action=index");
            exit();
        }

        $stmt = $this->transaksi->readByUser($_SESSION['user_id']);
        $transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        require_once 'views/user/riwayat.php';
    }

    // Detail Tiket
    public function detailTiket() {
        if(session_status() == PHP_SESSION_NONE) session_start();
        
        if(!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
            header("Location: index.php?module=user&action=dashboard");
            exit();
        }

        $this->transaksi->id_transaksi = $_GET['id'];
        $transaksi = $this->transaksi->readOne();
        
        // Verify ownership
        if(!$transaksi || $transaksi['id_user'] != $_SESSION['user_id']) {
            header("Location: index.php?module=user&action=dashboard");
            exit();
        }

        $detailTransaksi = $this->transaksi->getDetailWithTickets($_GET['id']);
        
        require_once 'views/user/detail_tiket.php';
    }
}
?>