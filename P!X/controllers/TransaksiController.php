<?php
// controllers/TransaksiController.php - FIXED VERSION with Pre-Sale Info
require_once 'config/database.php';
require_once 'models/Transaksi.php';
require_once 'models/DetailTransaksi.php';
require_once 'models/Jadwal.php';
require_once 'models/Film.php';

class TransaksiController {
    private $db;
    private $transaksi;
    private $detailTransaksi;
    private $jadwal;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->transaksi = new Transaksi($this->db);
        $this->detailTransaksi = new DetailTransaksi($this->db);
        $this->jadwal = new Jadwal($this->db);
    }

    // Halaman Pilih Jadwal - FIXED
    public function pilihJadwal() {
        if(session_status() == PHP_SESSION_NONE) session_start();
        
        if(!isset($_SESSION['user_id'])) {
            $_SESSION['flash'] = 'Silakan login terlebih dahulu!';
            header("Location: index.php?module=auth&action=index");
            exit();
        }

        if(!isset($_GET['id_film'])) {
            header("Location: index.php?module=film");
            exit();
        }

        $id_film = $_GET['id_film'];
        
        // Get Film Info - FIXED
        $film = new Film($this->db);
        $film->id_film = $id_film;
        $filmData = $film->readOne() ? $film : null;

        // Get Available Schedules with complete data
        $query = "SELECT jt.*, f.judul_film, b.nama_bioskop, b.kota 
                  FROM Jadwal_Tayang jt
                  LEFT JOIN Film f ON jt.id_film = f.id_film
                  LEFT JOIN Bioskop b ON jt.id_bioskop = b.id_bioskop
                  WHERE jt.id_film = :id_film
                  ORDER BY jt.tanggal_tayang ASC, jt.jam_mulai ASC";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id_film', $id_film);
        $stmt->execute();
        $jadwals = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Filter only future schedules
        $today = date('Y-m-d');
        $jadwals = array_filter($jadwals, function($j) use ($today) {
            return $j['tanggal_tayang'] >= $today;
        });

        require_once 'views/transaksi/pilih_jadwal.php';
    }

    // Halaman Booking Tiket - FIXED
    public function booking() {
        if(session_status() == PHP_SESSION_NONE) session_start();
        
        if(!isset($_SESSION['user_id']) || !isset($_GET['id_jadwal'])) {
            header("Location: index.php?module=film");
            exit();
        }

        $id_jadwal = $_GET['id_jadwal'];
        
        // Get Complete Jadwal Info with JOIN - FIXED
        $query = "SELECT jt.*, f.judul_film, f.poster_url, b.nama_bioskop, b.kota, b.alamat_bioskop
                  FROM Jadwal_Tayang jt
                  LEFT JOIN Film f ON jt.id_film = f.id_film
                  LEFT JOIN Bioskop b ON jt.id_bioskop = b.id_bioskop
                  WHERE jt.id_tayang = :id_jadwal";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id_jadwal', $id_jadwal);
        $stmt->execute();
        $jadwalData = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if(!$jadwalData) {
            $_SESSION['flash'] = 'Jadwal tidak ditemukan!';
            header("Location: index.php?module=film");
            exit();
        }

        // Pass as object for compatibility with view
        $this->jadwal = (object) $jadwalData;

        // Get Kursi Terpesan
        $kursiTerpesan = $this->detailTransaksi->getKursiTerpesanByJadwal($id_jadwal);
        
        require_once 'views/transaksi/booking.php';
    }

    // Process Booking dengan Random Seat
    public function prosesBooking() {
        if(session_status() == PHP_SESSION_NONE) session_start();
        
        if(!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] != 'POST') {
            header("Location: index.php?module=film");
            exit();
        }

        $id_jadwal = $_POST['id_jadwal'];
        $jumlah_tiket = isset($_POST['jumlah_tiket']) ? (int)$_POST['jumlah_tiket'] : 0;
        $metode_pembayaran = $_POST['metode_pembayaran'];

        if($jumlah_tiket < 1 || $jumlah_tiket > 10) {
            $_SESSION['flash'] = 'Jumlah tiket harus antara 1-10!';
            header("Location: index.php?module=transaksi&action=booking&id_jadwal=" . $id_jadwal);
            exit();
        }

        // Get Jadwal Info
        $this->jadwal->id_tayang = $id_jadwal;
        $this->jadwal->readOne();

        // Generate Random Kursi
        $kursiTerpilih = [];
        for($i = 0; $i < $jumlah_tiket; $i++) {
            $kursi = $this->detailTransaksi->generateRandomKursi($id_jadwal);
            
            if($kursi === null) {
                $_SESSION['flash'] = 'Maaf, tidak ada kursi tersedia!';
                header("Location: index.php?module=transaksi&action=booking&id_jadwal=" . $id_jadwal);
                exit();
            }
            
            $kursiTerpilih[] = $kursi;
        }

        // Create Transaksi
        $this->transaksi->id_user = $_SESSION['user_id'];
        $this->transaksi->id_admin = null;
        $this->transaksi->jumlah_tiket = $jumlah_tiket;
        $this->transaksi->total_harga = $jumlah_tiket * $this->jadwal->harga_tiket;
        $this->transaksi->metode_pembayaran = $metode_pembayaran;
        $this->transaksi->status_pembayaran = 'berhasil';
        $this->transaksi->tanggal_pembayaran = date('Y-m-d H:i:s');

        if($this->transaksi->create()) {
            // Create Detail Transaksi for each ticket
            foreach($kursiTerpilih as $kursi) {
                $this->detailTransaksi->id_transaksi = $this->transaksi->id_transaksi;
                $this->detailTransaksi->id_jadwal_tayang = $id_jadwal;
                $this->detailTransaksi->nomor_kursi = $kursi;
                $this->detailTransaksi->harga_tiket = $this->jadwal->harga_tiket;
                $this->detailTransaksi->jenis_tiket = 'reguler';
                $this->detailTransaksi->create();
            }

            $_SESSION['flash'] = 'Booking berhasil! Kode Booking: ' . $this->transaksi->kode_booking;
            header("Location: index.php?module=transaksi&action=konfirmasi&kode=" . $this->transaksi->kode_booking);
            exit();
        } else {
            $_SESSION['flash'] = 'Gagal melakukan booking!';
            header("Location: index.php?module=transaksi&action=booking&id_jadwal=" . $id_jadwal);
            exit();
        }
    }

    // Konfirmasi Pembayaran (Struk)
    public function konfirmasi() {
        if(session_status() == PHP_SESSION_NONE) session_start();
        
        if(!isset($_SESSION['user_id']) || !isset($_GET['kode'])) {
            header("Location: index.php?module=user&action=dashboard");
            exit();
        }

        $kode_booking = $_GET['kode'];
        $transaksi = $this->transaksi->getByKodeBooking($kode_booking);
        
        if(!$transaksi || $transaksi['id_user'] != $_SESSION['user_id']) {
            header("Location: index.php?module=user&action=dashboard");
            exit();
        }

        $detailTransaksi = $this->transaksi->getDetailWithTickets($transaksi['id_transaksi']);
        
        require_once 'views/transaksi/konfirmasi.php';
    }

    // Update Status (Admin Only)
    public function updateStatus() {
        if(session_status() == PHP_SESSION_NONE) session_start();
        
        if(!isset($_SESSION['admin_id']) || $_SERVER['REQUEST_METHOD'] != 'POST') {
            header("Location: index.php?module=transaksi");
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

        header("Location: index.php?module=transaksi");
        exit();
    }

    // Detail Transaksi (Admin)
    public function detail() {
        if(session_status() == PHP_SESSION_NONE) session_start();
        
        if(!isset($_SESSION['admin_id']) || !isset($_GET['id'])) {
            header("Location: index.php?module=transaksi");
            exit();
        }

        $detailTransaksi = $this->transaksi->getDetailWithTickets($_GET['id']);
        
        if(!$detailTransaksi) {
            header("Location: index.php?module=transaksi");
            exit();
        }

        require_once 'views/transaksi/detail.php';
    }
}
?>