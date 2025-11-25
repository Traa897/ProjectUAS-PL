<?php
// controllers/FilmController.php - Tanpa Aktor
require_once 'config/database.php';
require_once 'models/Film.php';
require_once 'models/Genre.php';

class FilmController {
    private $db;
    private $film;
    private $genre;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->film = new Film($this->db);
        $this->genre = new Genre($this->db);
    }

    // INDEX - List all films (Welcome Page)
    public function index() {
        $search = isset($_GET['search']) ? $_GET['search'] : '';
        $genre_filter = isset($_GET['genre']) ? $_GET['genre'] : '';

        if($search != '') {
            $stmt = $this->film->search($search);
        } else if($genre_filter != '') {
            $stmt = $this->film->readByGenre($genre_filter);
        } else {
            $stmt = $this->film->readAll();
        }

        $films = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $genres = $this->genre->readAll()->fetchAll(PDO::FETCH_ASSOC);
        
        require_once 'views/film/index.php';
    }

    // SHOW - Detail film
    public function show() {
        if(isset($_GET['id'])) {
            $this->film->id_film = $_GET['id'];
            $this->film->readOne();
            
            // Get film data tanpa aktor
            $filmData = [
                'id_film' => $this->film->id_film,
                'judul_film' => $this->film->judul_film,
                'tahun_rilis' => $this->film->tahun_rilis,
                'durasi_menit' => $this->film->durasi_menit,
                'sipnosis' => $this->film->sipnosis,
                'rating' => $this->film->rating,
                'poster_url' => $this->film->poster_url,
                'id_genre' => $this->film->id_genre
            ];
            
            // Get genre name
            $query = "SELECT nama_genre FROM Genre WHERE id_genre = :id_genre";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id_genre', $this->film->id_genre);
            $stmt->execute();
            $genre = $stmt->fetch(PDO::FETCH_ASSOC);
            $filmData['nama_genre'] = $genre ? $genre['nama_genre'] : 'Unknown';
            
            require_once 'views/film/show.php';
        } else {
            header("Location: index.php?module=film&error=Film tidak ditemukan");
            exit();
        }
    }

    // CREATE - Show form
    public function create() {
        $genres = $this->genre->readAll()->fetchAll(PDO::FETCH_ASSOC);
        require_once 'views/film/create.php';
    }

    // STORE - Save new film
    public function store() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->film->judul_film = $_POST['judul_film'];
            $this->film->tahun_rilis = $_POST['tahun_rilis'];
            $this->film->durasi_menit = $_POST['durasi_menit'];
            $this->film->sipnosis = $_POST['sipnosis'];
            $this->film->rating = $_POST['rating'];
            $this->film->poster_url = $_POST['poster_url'];
            $this->film->id_genre = $_POST['id_genre'];

            if($this->film->create()) {
                header("Location: index.php?module=film&message=Film berhasil ditambahkan!");
                exit();
            } else {
                header("Location: index.php?module=film&action=create&error=Gagal menambahkan film");
                exit();
            }
        }
    }

    // EDIT - Show edit form
    public function edit() {
        if(isset($_GET['id'])) {
            $this->film->id_film = $_GET['id'];
            if($this->film->readOne()) {
                $genres = $this->genre->readAll()->fetchAll(PDO::FETCH_ASSOC);
                require_once 'views/film/edit.php';
            } else {
                header("Location: index.php?module=film&error=Film tidak ditemukan");
                exit();
            }
        }
    }

    // UPDATE - Update film
    public function update() {
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
                header("Location: index.php?module=film&message=Film berhasil diupdate!");
                exit();
            } else {
                header("Location: index.php?module=film&action=edit&id=" . $this->film->id_film . "&error=Gagal mengupdate film");
                exit();
            }
        }
    }

    // DELETE - Delete film
    public function delete() {
        if(isset($_GET['id'])) {
            $this->film->id_film = $_GET['id'];
            if($this->film->delete()) {
                header("Location: index.php?module=film&message=Film berhasil dihapus!");
                exit();
            } else {
                header("Location: index.php?module=film&error=Gagal menghapus film");
                exit();
            }
        }
    }
}
?>