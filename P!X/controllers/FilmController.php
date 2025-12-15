<?php
// controllers/FilmController.php - COMPLETE FIXED VERSION

require_once 'config/database.php';
require_once 'models/BaseModel.php';
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

    /**
     * ✅ INDEX - Halaman daftar film (PUBLIC)
     * Menampilkan HANYA film yang punya jadwal >= hari ini
     */
    public function index() {
        if(session_status() == PHP_SESSION_NONE) session_start();
        
        $search = isset($_GET['search']) ? $_GET['search'] : '';
        $genre_filter = isset($_GET['genre']) ? $_GET['genre'] : '';
        $status_filter = isset($_GET['status']) ? $_GET['status'] : '';
        
        // Query: Hanya film dengan jadwal >= hari ini
        if($status_filter != '') {
            switch($status_filter) {
                case 'akan_tayang':
                    $stmt = $this->film->readAkanTayang();
                    break;
                case 'sedang_tayang':
                    $stmt = $this->film->readSedangTayang();
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
        
        // Deduplicate films
        $uniqueFilms = [];
        $seenIds = [];
        
        foreach($films as $film) {
            $filmId = (int)$film['id_film'];
            
            if(!in_array($filmId, $seenIds, true)) {
                $seenIds[] = $filmId;
                $uniqueFilms[] = $film;
            }
        }
        
        $films = $uniqueFilms;
        
        // Set status untuk setiap film
        foreach($films as $key => &$film) {
            $status = $this->film->getFilmStatus($film['id_film']);
            $film['status'] = $status;
            
            // Filter jika ada status filter
            if($status_filter != '') {
                if($status_filter == 'akan_tayang' && $status != 'Akan Tayang') {
                    unset($films[$key]);
                    continue;
                }
                if($status_filter == 'sedang_tayang' && $status != 'Sedang Tayang') {
                    unset($films[$key]);
                    continue;
                }
            }
        }
        unset($film);
        
        $films = array_values($films);
        
        // Get all genres for filter
        $genres = $this->genre->readAll()->fetchAll(PDO::FETCH_ASSOC);
        
        // Count by status
        $countAkanTayang = $this->film->countByStatus('akan_tayang');
        $countSedangTayang = $this->film->countByStatus('sedang_tayang');
        
        // Load view dengan header PUBLIC
        require_once 'views/film/index.php';
    }

    /**
     * ✅ SHOW - Detail film (PUBLIC)
     * Siapa saja bisa lihat detail, tapi BOOKING perlu login
     */
    public function show() {
        if(session_status() == PHP_SESSION_NONE) session_start();
        
        if(!isset($_GET['id'])) {
            header("Location: index.php?module=film&error=Film tidak ditemukan");
            exit();
        }
        
        $this->film->id_film = $_GET['id'];
        
        if($this->film->readOne()) {
            $filmData = [
                'id_film' => $this->film->id_film,
                'judul_film' => $this->film->judul_film,
                'tahun_rilis' => $this->film->tahun_rilis,
                'durasi_menit' => $this->film->durasi_menit,
                'sipnosis' => $this->film->sipnosis,
                'rating' => $this->film->rating,
                'poster_url' => $this->film->poster_url,
                'id_genre' => $this->film->id_genre,
                'nama_genre' => $this->film->nama_genre ?? 'Unknown'
            ];
            
            // Load view dengan header PUBLIC
            require_once 'views/film/show.php';
        } else {
            $_SESSION['flash'] = '❌ Film tidak ditemukan!';
            header("Location: index.php?module=film");
            exit();
        }
    }

    /**
     * ✅ CREATE - Form tambah film (ADMIN ONLY)
     * Sudah ada protection di index.php
     */
    public function create() {
        if(session_status() == PHP_SESSION_NONE) session_start();
        
        // Double check admin
        if(!isset($_SESSION['admin_id'])) {
            $_SESSION['flash'] = '⚠️ Anda harus login sebagai admin!';
            header("Location: index.php?module=auth&action=index");
            exit();
        }
        
        $genres = $this->genre->readAll()->fetchAll(PDO::FETCH_ASSOC);
        
        // Load view dengan header ADMIN
        require_once 'views/film/create.php';
    }

    /**
     * ✅ STORE - Simpan film baru (ADMIN ONLY)
     */
    public function store() {
        if(session_status() == PHP_SESSION_NONE) session_start();
        
        // Protection
        if(!isset($_SESSION['admin_id'])) {
            $_SESSION['flash'] = '⚠️ Anda harus login sebagai admin!';
            header("Location: index.php?module=auth&action=index");
            exit();
        }
        
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Validasi manual sederhana
            if(empty($_POST['judul_film']) || empty($_POST['tahun_rilis']) || 
               empty($_POST['durasi_menit']) || empty($_POST['sipnosis']) || 
               empty($_POST['rating']) || empty($_POST['id_genre'])) {
                $_SESSION['flash'] = '❌ Semua field wajib diisi!';
                header("Location: index.php?module=admin&action=createFilm");
                exit();
            }
            
            // Check duplicate
            $queryCheck = "SELECT COUNT(*) as count FROM Film WHERE judul_film = :judul_film";
            $stmtCheck = $this->db->prepare($queryCheck);
            $stmtCheck->bindParam(':judul_film', $_POST['judul_film']);
            $stmtCheck->execute();
            $resultCheck = $stmtCheck->fetch(PDO::FETCH_ASSOC);
            
            if($resultCheck['count'] > 0) {
                $_SESSION['flash'] = '❌ Film dengan judul tersebut sudah ada!';
                header("Location: index.php?module=admin&action=createFilm");
                exit();
            }
            
            // Populate data
            $this->film->judul_film = trim($_POST['judul_film']);
            $this->film->tahun_rilis = (int)$_POST['tahun_rilis'];
            $this->film->durasi_menit = (int)$_POST['durasi_menit'];
            $this->film->sipnosis = trim($_POST['sipnosis']);
            $this->film->rating = (float)$_POST['rating'];
            $this->film->poster_url = trim($_POST['poster_url']) ?: 'https://via.placeholder.com/300x450';
            $this->film->id_genre = (int)$_POST['id_genre'];

            if($this->film->create()) {
                $_SESSION['flash'] = '✅ Film berhasil ditambahkan!';
                header("Location: index.php?module=admin&action=dashboard");
                exit();
            } else {
                $_SESSION['flash'] = '❌ Gagal menambahkan film!';
                header("Location: index.php?module=admin&action=createFilm");
                exit();
            }
        }
    }

    /**
     * ✅ EDIT - Form edit film (ADMIN ONLY)
     */
    public function edit() {
        if(session_status() == PHP_SESSION_NONE) session_start();
        
        // Protection
        if(!isset($_SESSION['admin_id'])) {
            $_SESSION['flash'] = '⚠️ Anda harus login sebagai admin!';
            header("Location: index.php?module=auth&action=index");
            exit();
        }
        
        if(!isset($_GET['id'])) {
            $_SESSION['flash'] = '❌ ID film tidak valid!';
            header("Location: index.php?module=admin&action=dashboard");
            exit();
        }
        
        $this->film->id_film = $_GET['id'];
        
        if($this->film->readOne()) {
            $genres = $this->genre->readAll()->fetchAll(PDO::FETCH_ASSOC);
            
            // Load view dengan header ADMIN
            require_once 'views/film/edit.php';
        } else {
            $_SESSION['flash'] = '❌ Film tidak ditemukan!';
            header("Location: index.php?module=admin&action=dashboard");
            exit();
        }
    }

    /**
     * ✅ UPDATE - Update film (ADMIN ONLY)
     */
    public function update() {
        if(session_status() == PHP_SESSION_NONE) session_start();
        
        // Protection
        if(!isset($_SESSION['admin_id'])) {
            $_SESSION['flash'] = '⚠️ Anda harus login sebagai admin!';
            header("Location: index.php?module=auth&action=index");
            exit();
        }
        
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Validasi
            if(empty($_POST['id_film']) || empty($_POST['judul_film']) || 
               empty($_POST['tahun_rilis']) || empty($_POST['durasi_menit']) || 
               empty($_POST['sipnosis']) || empty($_POST['rating']) || 
               empty($_POST['id_genre'])) {
                $_SESSION['flash'] = '❌ Semua field wajib diisi!';
                header("Location: index.php?module=admin&action=editFilm&id=" . ($_POST['id_film'] ?? ''));
                exit();
            }
            
            // Populate data
            $this->film->id_film = (int)$_POST['id_film'];
            $this->film->judul_film = trim($_POST['judul_film']);
            $this->film->tahun_rilis = (int)$_POST['tahun_rilis'];
            $this->film->durasi_menit = (int)$_POST['durasi_menit'];
            $this->film->sipnosis = trim($_POST['sipnosis']);
            $this->film->rating = (float)$_POST['rating'];
            $this->film->poster_url = trim($_POST['poster_url']) ?: 'https://via.placeholder.com/300x450';
            $this->film->id_genre = (int)$_POST['id_genre'];

            if($this->film->update()) {
                $_SESSION['flash'] = '✅ Film berhasil diupdate!';
                header("Location: index.php?module=admin&action=dashboard");
                exit();
            } else {
                $_SESSION['flash'] = '❌ Gagal mengupdate film!';
                header("Location: index.php?module=admin&action=editFilm&id=" . $this->film->id_film);
                exit();
            }
        }
    }

    /**
     * ✅ DELETE - Hapus film (ADMIN ONLY)
     */
    public function delete() {
        if(session_status() == PHP_SESSION_NONE) session_start();
        
        // Protection
        if(!isset($_SESSION['admin_id'])) {
            $_SESSION['flash'] = '⚠️ Anda harus login sebagai admin!';
            header("Location: index.php?module=auth&action=index");
            exit();
        }
        
        if(!isset($_GET['id'])) {
            $_SESSION['flash'] = '❌ ID film tidak valid!';
            header("Location: index.php?module=admin&action=dashboard");
            exit();
        }
        
        $this->film->id_film = $_GET['id'];
        
        if($this->film->delete()) {
            $_SESSION['flash'] = '✅ Film berhasil dihapus!';
        } else {
            $_SESSION['flash'] = '❌ Gagal menghapus film! Mungkin masih ada jadwal yang terkait.';
        }
        
        header("Location: index.php?module=admin&action=dashboard");
        exit();
    }
}
?>