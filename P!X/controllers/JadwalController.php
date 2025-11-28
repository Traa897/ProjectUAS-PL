<?php
// controllers/JadwalController.php - FIXED VERSION
require_once 'config/database.php';
require_once 'models/Jadwal.php';
require_once 'models/Film.php';
require_once 'models/Bioskop.php';

class JadwalController {
    private $db;
    private $jadwal;
    private $film;
    private $bioskop;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->jadwal = new Jadwal($this->db);
        $this->film = new Film($this->db);
        $this->bioskop = new Bioskop($this->db);
    }

    // INDEX - FIXED: Tampilkan SEMUA jadwal secara default
    public function index() {
        $date_filter = isset($_GET['date']) ? $_GET['date'] : '';
        $film_filter = isset($_GET['film']) ? $_GET['film'] : '';
        $bioskop_filter = isset($_GET['bioskop']) ? $_GET['bioskop'] : '';

        // Jika ada filter, gunakan filter
        if($date_filter != '') {
            $stmt = $this->jadwal->readByDate($date_filter);
        } elseif($film_filter != '') {
            $stmt = $this->jadwal->readByFilm($film_filter);
        } elseif($bioskop_filter != '') {
            $stmt = $this->jadwal->readByBioskop($bioskop_filter);
        } else {
            // PERBAIKAN: Tampilkan SEMUA jadwal tanpa filter
            $stmt = $this->jadwal->readAll();
        }

        $jadwals = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Get list film dan bioskop untuk filter
        $films = $this->film->readAll()->fetchAll(PDO::FETCH_ASSOC);
        $bioskops = $this->bioskop->readAll()->fetchAll(PDO::FETCH_ASSOC);
        
        require_once 'views/jadwal/index.php';
    }

    public function create() {
        if(session_status() == PHP_SESSION_NONE) session_start();
        
        // Cek apakah admin
        if(!isset($_SESSION['admin_id'])) {
            $_SESSION['flash'] = 'Anda harus login sebagai admin!';
            header("Location: index.php?module=auth&action=index");
            exit();
        }
        
        $films = $this->film->readAll()->fetchAll(PDO::FETCH_ASSOC);
        $bioskops = $this->bioskop->readAll()->fetchAll(PDO::FETCH_ASSOC);
        require_once 'views/jadwal/create.php';
    }

    public function store() {
        if(session_status() == PHP_SESSION_NONE) session_start();
        
        if(!isset($_SESSION['admin_id'])) {
            header("Location: index.php?module=auth&action=index");
            exit();
        }
        
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->jadwal->id_film = $_POST['id_film'];
            $this->jadwal->id_bioskop = $_POST['id_bioskop'];
            $this->jadwal->nama_tayang = $_POST['nama_tayang'];
            $this->jadwal->tanggal_tayang = $_POST['tanggal_tayang'];
            $this->jadwal->jam_mulai = $_POST['jam_mulai'];
            $this->jadwal->jam_selesai = $_POST['jam_selesai'];
            $this->jadwal->harga_tiket = $_POST['harga_tiket'];

            if($this->jadwal->create()) {
                $_SESSION['flash'] = 'Jadwal berhasil ditambahkan!';
                header("Location: index.php?module=jadwal");
                exit();
            } else {
                header("Location: index.php?module=jadwal&action=create&error=Gagal menambahkan jadwal");
                exit();
            }
        }
    }

    public function edit() {
        if(session_status() == PHP_SESSION_NONE) session_start();
        
        if(!isset($_SESSION['admin_id'])) {
            header("Location: index.php?module=auth&action=index");
            exit();
        }
        
        if(isset($_GET['id'])) {
            $this->jadwal->id_tayang = $_GET['id'];
            if($this->jadwal->readOne()) {
                $films = $this->film->readAll()->fetchAll(PDO::FETCH_ASSOC);
                $bioskops = $this->bioskop->readAll()->fetchAll(PDO::FETCH_ASSOC);
                require_once 'views/jadwal/edit.php';
            } else {
                header("Location: index.php?module=jadwal&error=Jadwal tidak ditemukan");
                exit();
            }
        }
    }

    public function update() {
        if(session_status() == PHP_SESSION_NONE) session_start();
        
        if(!isset($_SESSION['admin_id'])) {
            header("Location: index.php?module=auth&action=index");
            exit();
        }
        
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->jadwal->id_tayang = $_POST['id_tayang'];
            $this->jadwal->id_film = $_POST['id_film'];
            $this->jadwal->id_bioskop = $_POST['id_bioskop'];
            $this->jadwal->nama_tayang = $_POST['nama_tayang'];
            $this->jadwal->tanggal_tayang = $_POST['tanggal_tayang'];
            $this->jadwal->jam_mulai = $_POST['jam_mulai'];
            $this->jadwal->jam_selesai = $_POST['jam_selesai'];
            $this->jadwal->harga_tiket = $_POST['harga_tiket'];

            if($this->jadwal->update()) {
                $_SESSION['flash'] = 'Jadwal berhasil diupdate!';
                header("Location: index.php?module=jadwal");
                exit();
            } else {
                header("Location: index.php?module=jadwal&action=edit&id=" . $this->jadwal->id_tayang . "&error=Gagal mengupdate jadwal");
                exit();
            }
        }
    }

    public function delete() {
        if(session_status() == PHP_SESSION_NONE) session_start();
        
        if(!isset($_SESSION['admin_id'])) {
            header("Location: index.php?module=auth&action=index");
            exit();
        }
        
        if(isset($_GET['id'])) {
            $this->jadwal->id_tayang = $_GET['id'];
            if($this->jadwal->delete()) {
                $_SESSION['flash'] = 'Jadwal berhasil dihapus!';
                header("Location: index.php?module=jadwal");
                exit();
            } else {
                header("Location: index.php?module=jadwal&error=Gagal menghapus jadwal");
                exit();
            }
        }
    }
}
?>