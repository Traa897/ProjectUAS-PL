<?php
// controllers/AktorController.php
require_once 'config/database.php';
require_once 'models/Aktor.php';

class AktorController {
    private $db;
    private $aktor;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->aktor = new Aktor($this->db);
    }

    // INDEX - List all actors
    public function index() {
        $search = isset($_GET['search']) ? $_GET['search'] : '';

        if($search != '') {
            $stmt = $this->aktor->search($search);
        } else {
            $stmt = $this->aktor->readAll();
        }

        $aktors = $stmt->fetchAll(PDO::FETCH_ASSOC);
        require_once 'views/aktor/index.php';
    }

    // CREATE - Show form
    public function create() {
        require_once 'views/aktor/create.php';
    }

    // STORE - Save new actor
    public function store() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->aktor->nama_aktor = $_POST['nama_aktor'];
            $this->aktor->tanggal_lahir = $_POST['tanggal_lahir'];
            $this->aktor->negara_asal = $_POST['negara_asal'];
            $this->aktor->photo_url = $_POST['photo_url'];

            if($this->aktor->create()) {
                header("Location: index.php?module=aktor&message=Aktor berhasil ditambahkan!");
                exit();
            } else {
                header("Location: index.php?module=aktor&action=create&error=Gagal menambahkan aktor");
                exit();
            }
        }
    }

    // EDIT - Show edit form
    public function edit() {
        if(isset($_GET['id'])) {
            $this->aktor->id_aktor = $_GET['id'];
            if($this->aktor->readOne()) {
                require_once 'views/aktor/edit.php';
            } else {
                header("Location: index.php?module=aktor&error=Aktor tidak ditemukan");
                exit();
            }
        }
    }

    // UPDATE - Update actor
    public function update() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->aktor->id_aktor = $_POST['id_aktor'];
            $this->aktor->nama_aktor = $_POST['nama_aktor'];
            $this->aktor->tanggal_lahir = $_POST['tanggal_lahir'];
            $this->aktor->negara_asal = $_POST['negara_asal'];
            $this->aktor->photo_url = $_POST['photo_url'];

            if($this->aktor->update()) {
                header("Location: index.php?module=aktor&message=Aktor berhasil diupdate!");
                exit();
            } else {
                header("Location: index.php?module=aktor&action=edit&id=" . $this->aktor->id_aktor . "&error=Gagal mengupdate aktor");
                exit();
            }
        }
    }

    // DELETE - Delete actor
    public function delete() {
        if(isset($_GET['id'])) {
            $this->aktor->id_aktor = $_GET['id'];
            if($this->aktor->delete()) {
                header("Location: index.php?module=aktor&message=Aktor berhasil dihapus!");
                exit();
            } else {
                header("Location: index.php?module=aktor&error=Gagal menghapus aktor");
                exit();
            }
        }
    }
}
?>