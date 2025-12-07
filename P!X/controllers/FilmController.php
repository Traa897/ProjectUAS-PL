<?php
// controllers/FilmController.php - FULL VERSION WITH STATUS & SECURITY
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
        $status_filter = isset($_GET['status']) ? $_GET['status'] : '';

        // Filter by Status
        if($status_filter != '') {
            switch($status_filter) {
                case 'akan_tayang':
                    $stmt = $this->film->readAkanTayang();
                    break;
                case 'sedang_tayang':
                    $stmt = $this->film->readSedangTayang();
                    break;
                case 'telah_tayang':
                    $stmt = $this->film->readTelahTayang();
                    break;
                default:
                    $stmt = $this->film->readAll();
            }
        } elseif($search != '') {
            $stmt = $this->film->search($search);
        } elseif($genre_filter != '') {
            $stmt = $this->film->readByGenre($genre_filter);
        } else {
            $stmt = $this->film->readAll();
        }

        $films = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Add status information to each film
        foreach($films as &$film) {
            $film['status'] = $this->film->getFilmStatus($film['id_film']);
        }
        
        $genres = $this->genre->readAll()->fetchAll(PDO::FETCH_ASSOC);
        
        // Count by status
        $countAkanTayang = $this->film->countByStatus('akan_tayang');
        $countSedangTayang = $this->film->countByStatus('sedang_tayang');
        $countTelahTayang = $this->film->countByStatus('telah_tayang');
        
        require_once 'views/film/index.php';
    }

    // SHOW - Detail film
    public function show() {
        if(isset($_GET['id'])) {
            $this->film->id_film = $_GET['id'];
            $this->film->readOne();
            
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

    // CREATE - Show form (ADMIN ONLY)
    public function create() {
        if(session_status() == PHP_SESSION_NONE) session_start();
        
        if(!isset($_SESSION['admin_id'])) {
            $_SESSION['flash'] = 'Anda harus login sebagai admin untuk mengakses halaman ini!';
            header("Location: index.php?module=film");
            exit();
        }
        
        $genres = $this->genre->readAll()->fetchAll(PDO::FETCH_ASSOC);
        require_once 'views/film/create.php';
    }

    // STORE - Save new film (ADMIN ONLY)
    public function store() {
        if(session_status() == PHP_SESSION_NONE) session_start();
        
        if(!isset($_SESSION['admin_id'])) {
            $_SESSION['flash'] = 'Anda harus login sebagai admin!';
            header("Location: index.php?module=film");
            exit();
        }
        
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
                header("Location: index.php?module=film&action=create");
                exit();
            }
        }
    }

    // EDIT - Show edit form (ADMIN ONLY)
    public function edit() {
        if(session_status() == PHP_SESSION_NONE) session_start();
        
        if(!isset($_SESSION['admin_id'])) {
            $_SESSION['flash'] = 'Anda harus login sebagai admin!';
            header("Location: index.php?module=film");
            exit();
        }
        
        if(isset($_GET['id'])) {
            $this->film->id_film = $_GET['id'];
            if($this->film->readOne()) {
                $genres = $this->genre->readAll()->fetchAll(PDO::FETCH_ASSOC);
                require_once 'views/film/edit.php';
            } else {
                $_SESSION['flash'] = 'Film tidak ditemukan!';
                header("Location: index.php?module=film");
                exit();
            }
        }
    }

    // UPDATE - Update film (ADMIN ONLY)
    public function update() {
        if(session_status() == PHP_SESSION_NONE) session_start();
        
        if(!isset($_SESSION['admin_id'])) {
            $_SESSION['flash'] = 'Anda harus login sebagai admin!';
            header("Location: index.php?module=film");
            exit();
        }
        
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
                header("Location: index.php?module=film&action=edit&id=" . $this->film->id_film);
                exit();
            }
        }
    }

    // DELETE - Delete film (ADMIN ONLY)
    public function delete() {
        if(session_status() == PHP_SESSION_NONE) session_start();
        
        if(!isset($_SESSION['admin_id'])) {
            $_SESSION['flash'] = 'Anda harus login sebagai admin!';
            header("Location: index.php?module=film");
            exit();
        }
        
        if(isset($_GET['id'])) {
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
}
?>