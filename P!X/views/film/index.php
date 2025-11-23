<?php require_once 'views/layouts/header.php'; ?>

<div class="hero-section">
    <div class="hero-content">
        <h1>Daftar Film</h1>
        <p>Koleksi Film Terbaik Indonesia</p>
        
        <form method="GET" action="index.php" class="hero-search">
            <input type="hidden" name="module" value="film">
            <input type="text" name="search" placeholder="Cari film berdasarkan judul..." 
                   value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
            <button type="submit" class="btn-search">Cari</button>
        </form>
    </div>
</div>

<div class="container">
    <?php if(isset($_GET['message'])): ?>
        <div class="alert alert-success">
             <?php echo htmlspecialchars($_GET['message']); ?>
        </div>
    <?php endif; ?>

    <?php if(isset($_GET['error'])): ?>
        <div class="alert alert-error">
             <?php echo htmlspecialchars($_GET['error']); ?>
        </div>
    <?php endif; ?>

    <div class="header-section">
        <h2>Semua Film (<?php echo count($films); ?>)</h2>
        <div>
            <?php if(isset($_SESSION) && isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin'): ?>
                <a href="index.php?module=film&action=create" class="btn btn-primary">Tambah</a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Filter Genre -->
    <div class="status-filter-section">
        <h2>Filter by Genre</h2>
        <div class="status-buttons">
            <a href="index.php?module=film" class="btn <?php echo (!isset($_GET['genre'])) ? 'btn-primary' : 'btn-secondary'; ?>">
                Semua Genre
            </a>
            <?php foreach($genres as $genre): ?>
                <a href="index.php?module=film&genre=<?php echo $genre['id_genre']; ?>" 
                   class="btn <?php echo (isset($_GET['genre']) && $_GET['genre'] == $genre['id_genre']) ? 'btn-primary' : 'btn-secondary'; ?>">
                    <?php echo htmlspecialchars($genre['nama_genre']); ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>

    <?php if(empty($films)): ?>
        <div class="empty-state">
            <p> Tidak ada film yang ditemukan</p>
            <a href="index.php?module=film&action=create" class="btn btn-primary">Tambah Film Pertama</a>
        </div>
    <?php else: ?>
        <div class="movie-scroll">
            <?php foreach($films as $film): ?>
                <div class="movie-card-scroll">
                    <div class="movie-poster-scroll">
                        <img src="<?php echo htmlspecialchars($film['poster_url'] ?? 'https://via.placeholder.com/150x225'); ?>" 
                             alt="<?php echo htmlspecialchars($film['judul_film']); ?>">
                        <div class="rating-badge">
                            <span class="rating-circle">
                                <svg viewBox="0 0 36 36">
                                    <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                                        fill="none" stroke="#204529" stroke-width="3"/>
                                    <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                                        fill="none" stroke="#21d07a" stroke-width="3"
                                        stroke-dasharray="<?php echo ($film['rating'] * 10) . ', 100'; ?>"/>
                                </svg>
                                <span class="rating-number"><?php echo number_format($film['rating'] * 10, 0); ?></span>
                            </span>
                        </div>
                        <div class="card-actions-overlay">
                            <a href="index.php?module=film&action=show&id=<?php echo $film['id_film']; ?>" class="btn btn-info btn-sm">👁️ Detail</a>
                            <?php if(isset($_SESSION) && isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin'): ?>
                                <a href="index.php?module=film&action=edit&id=<?php echo $film['id_film']; ?>" class="btn btn-warning btn-sm">✏️ Edit</a>
                                <a href="index.php?module=film&action=delete&id=<?php echo $film['id_film']; ?>" 
                                   class="btn btn-danger btn-sm" 
                                   onclick="return confirm('Hapus film <?php echo htmlspecialchars($film['judul_film']); ?>?')">🗑️ Hapus</a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="movie-info-scroll">
                        <h3><?php echo htmlspecialchars($film['judul_film']); ?></h3>
                        <p class="movie-date"><?php echo $film['tahun_rilis']; ?> • <?php echo $film['durasi_menit']; ?> menit</p>
                        <p style="font-size: 12px; color: #01b4e4;">
                            <?php echo htmlspecialchars($film['nama_genre'] ?? 'No Genre'); ?>
                        </p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once 'views/layouts/footer.php'; ?>