<?php
// controllers/BioskopController.php
require_once 'config/database.php';
require_once 'models/Bioskop.php';

class BioskopController {
    private $db;
    private $bioskop;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->bioskop = new Bioskop($this->db);
    }

    // INDEX - List all cinemas
    public function index() {
        $search = isset($_GET['search']) ? $_GET['search'] : '';
        $city = isset($_GET['city']) ? $_GET['city'] : '';

        if($search != '') {
            $stmt = $this->bioskop->search($search);
        } else if($city != '') {
            $stmt = $this->bioskop->readByCity($city);
        } else {
            $stmt = $this->bioskop->readAll();
        }

        $bioskops = $stmt->fetchAll(PDO::FETCH_ASSOC);
        require_once 'views/bioskop/index.php';
    }

    // SHOW - Detail cinema with schedules
    public function show() {
        if(isset($_GET['id'])) {
            $this->bioskop->id_bioskop = $_GET['id'];
            $bioskopData = $this->bioskop->getBioskopWithSchedules($_GET['id']);
            
            if($bioskopData) {
                // Get schedules for this cinema
                $query = "SELECT jt.*, f.judul_film, f.poster_url 
                          FROM Jadwal_Tayang jt
                          JOIN Film f ON jt.id_film = f.id_film
                          WHERE jt.id_bioskop = :id_bioskop
                          ORDER BY jt.tanggal_tayang DESC, jt.jam_mulai ASC";
                $stmt = $this->db->prepare($query);
                $stmt->bindParam(':id_bioskop', $_GET['id']);
                $stmt->execute();
                $schedules = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                require_once 'views/bioskop/show.php';
            } else {
                header("Location: index.php?module=bioskop&error=Bioskop tidak ditemukan");
                exit();
            }
        }
    }

    // CREATE - Show form
    public function create() {
        require_once 'views/bioskop/create.php';
    }

    // STORE - Save new cinema
    public function store() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->bioskop->nama_bioskop = $_POST['nama_bioskop'];
            $this->bioskop->kota = $_POST['kota'];
            $this->bioskop->alamat_bioskop = $_POST['alamat_bioskop'];
            $this->bioskop->jumlah_studio = $_POST['jumlah_studio'];
            $this->bioskop->logo_url = $_POST['logo_url'] ?? '';

            if($this->bioskop->create()) {
                header("Location: index.php?module=bioskop&message=Bioskop berhasil ditambahkan!");
                exit();
            } else {
                header("Location: index.php?module=bioskop&action=create&error=Gagal menambahkan bioskop");
                exit();
            }
        }
    }

    // EDIT - Show edit form
    public function edit() {
        if(isset($_GET['id'])) {
            $this->bioskop->id_bioskop = $_GET['id'];
            if($this->bioskop->readOne()) {
                require_once 'views/bioskop/edit.php';
            } else {
                header("Location: index.php?module=bioskop&error=Bioskop tidak ditemukan");
                exit();
            }
        }
    }

    // UPDATE - Update cinema
    public function update() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->bioskop->id_bioskop = $_POST['id_bioskop'];
            $this->bioskop->nama_bioskop = $_POST['nama_bioskop'];
            $this->bioskop->kota = $_POST['kota'];
            $this->bioskop->alamat_bioskop = $_POST['alamat_bioskop'];
            $this->bioskop->jumlah_studio = $_POST['jumlah_studio'];
            $this->bioskop->logo_url = $_POST['logo_url'] ?? '';

            if($this->bioskop->update()) {
                header("Location: index.php?module=bioskop&message=Bioskop berhasil diupdate!");
                exit();
            } else {
                header("Location: index.php?module=bioskop&action=edit&id=" . $this->bioskop->id_bioskop . "&error=Gagal mengupdate bioskop");
                exit();
            }
        }
    }

    // DELETE - Delete cinema
    public function delete() {
        if(isset($_GET['id'])) {
            $this->bioskop->id_bioskop = $_GET['id'];
            if($this->bioskop->delete()) {
                header("Location: index.php?module=bioskop&message=Bioskop berhasil dihapus!");
                exit();
            } else {
                header("Location: index.php?module=bioskop&error=Gagal menghapus bioskop");
                exit();
            }
        }
    }
}
?>