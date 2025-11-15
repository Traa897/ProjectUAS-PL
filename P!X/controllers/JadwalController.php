<?php
// controllers/JadwalController.php
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

    // INDEX - List all schedules
    public function index() {
        $date_filter = isset($_GET['date']) ? $_GET['date'] : '';
        $film_filter = isset($_GET['film']) ? $_GET['film'] : '';
        $bioskop_filter = isset($_GET['bioskop']) ? $_GET['bioskop'] : '';

        if($date_filter != '') {
            $stmt = $this->jadwal->readByDate($date_filter);
        } else if($film_filter != '') {
            $stmt = $this->jadwal->readByFilm($film_filter);
        } else if($bioskop_filter != '') {
            $stmt = $this->jadwal->readByBioskop($bioskop_filter);
        } else {
            $stmt = $this->jadwal->readAll();
        }

        $jadwals = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $films = $this->film->readAll()->fetchAll(PDO::FETCH_ASSOC);
        $bioskops = $this->bioskop->readAll()->fetchAll(PDO::FETCH_ASSOC);
        
        require_once 'views/jadwal/index.php';
    }

    // SHOW - Detail schedule
    public function show() {
        if(isset($_GET['id'])) {
            $this->jadwal->id_tayang = $_GET['id'];
            if($this->jadwal->readOne()) {
                require_once 'views/jadwal/show.php';
            } else {
                header("Location: index.php?module=jadwal&error=Jadwal tidak ditemukan");
                exit();
            }
        }
    }

    // CREATE - Show form
    public function create() {
        $films = $this->film->readAll()->fetchAll(PDO::FETCH_ASSOC);
        $bioskops = $this->bioskop->readAll()->fetchAll(PDO::FETCH_ASSOC);
        require_once 'views/jadwal/create.php';
    }

    // STORE - Save new schedule
    public function store() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->jadwal->id_film = $_POST['id_film'];
            $this->jadwal->id_bioskop = $_POST['id_bioskop'];
            $this->jadwal->nama_tayang = $_POST['nama_tayang'];
            $this->jadwal->tanggal_tayang = $_POST['tanggal_tayang'];
            $this->jadwal->jam_mulai = $_POST['jam_mulai'];
            $this->jadwal->jam_selesai = $_POST['jam_selesai'];
            $this->jadwal->harga_tiket = $_POST['harga_tiket'];

            if($this->jadwal->create()) {
                header("Location: index.php?module=jadwal&message=Jadwal berhasil ditambahkan!");
                exit();
            } else {
                header("Location: index.php?module=jadwal&action=create&error=Gagal menambahkan jadwal");
                exit();
            }
        }
    }

    // EDIT - Show edit form
    public function edit() {
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

    // UPDATE - Update schedule
    public function update() {
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
                header("Location: index.php?module=jadwal&message=Jadwal berhasil diupdate!");
                exit();
            } else {
                header("Location: index.php?module=jadwal&action=edit&id=" . $this->jadwal->id_tayang . "&error=Gagal mengupdate jadwal");
                exit();
            }
        }
    }

    // DELETE - Delete schedule
    public function delete() {
        if(isset($_GET['id'])) {
            $this->jadwal->id_tayang = $_GET['id'];
            if($this->jadwal->delete()) {
                header("Location: index.php?module=jadwal&message=Jadwal berhasil dihapus!");
                exit();
            } else {
                header("Location: index.php?module=jadwal&error=Gagal menghapus jadwal");
                exit();
            }
        }
    }
}
?>