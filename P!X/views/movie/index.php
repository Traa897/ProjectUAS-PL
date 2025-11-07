<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="hero-section">
    <div class="hero-content">
        <h1>Selamat datang!</h1>
        <p>Film Tempat Bersantai dihari Weekend.</p>
        
        <form method="GET" action="index.php" class="hero-search">
            <input type="text" name="search" placeholder="Cari sebuah film, Sutradara..." 
                   value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
            <button type="submit" class="btn-search">Cari</button>
        </form>
    </div>
</div>

<div class="container">
    <?php if(isset($_GET['message'])): ?>
        <div class="alert alert-success">
            ✅ <?php echo htmlspecialchars($_GET['message']); ?>
        </div>
    <?php endif; ?>

    <?php if(isset($_GET['error'])): ?>
        <div class="alert alert-error">
            ❌ <?php echo htmlspecialchars($_GET['error']); ?>
        </div>
    <?php endif; ?>

    <div class="section-header">
        <h2>Trending</h2>
        <div class="filter-tabs">
            <a href="index.php" class="tab <?php echo (!isset($_GET['filter']) || $_GET['filter'] == 'all') ? 'active' : ''; ?>">
                Today
            </a>
            <a href="index.php?filter=sedang_tayang" class="tab <?php echo (isset($_GET['filter']) && $_GET['filter'] == 'sedang_tayang') ? 'active' : ''; ?>">
                This Week
            </a>
        </div>
    </div>

    <?php if(empty($movies)): ?>
        <div class="empty-state">
            <p>Tidak ada film yang ditemukan</p>
        </div>
    <?php else: ?>
        <div class="movie-scroll">
            <?php foreach($movies as $movie): ?>
                <div class="movie-card-scroll">
                    <div class="movie-poster-scroll">
                        <img src="<?php echo htmlspecialchars($movie['poster_url']); ?>" 
                             alt="<?php echo htmlspecialchars($movie['title']); ?>">
                        <div class="rating-badge">
                            <span class="rating-circle">
                                <svg viewBox="0 0 36 36">
                                    <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                                        fill="none" stroke="#204529" stroke-width="3"/>
                                    <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                                        fill="none" stroke="#21d07a" stroke-width="3"
                                        stroke-dasharray="<?php echo ($movie['rating'] * 10) . ', 100'; ?>"/>
                                </svg>
                                <span class="rating-number"><?php echo number_format($movie['rating'] * 10, 0); ?>%</span>
                            </span>
                        </div>
                        <div class="card-actions-overlay">
                            <a href="index.php?action=show&id=<?php echo $movie['id']; ?>" class="btn btn-info btn-sm">Detail</a>
                            <a href="index.php?action=edit&id=<?php echo $movie['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="index.php?action=delete&id=<?php echo $movie['id']; ?>" 
                               class="btn btn-danger btn-sm" 
                               onclick="return confirm('Hapus film ini?')">Hapus</a>
                        </div>
                    </div>
                    <div class="movie-info-scroll">
                        <h3><?php echo htmlspecialchars($movie['title']); ?></h3>
                        <p class="movie-date"><?php echo date('M d, Y', strtotime($movie['release_date'])); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="status-filter-section">
        <h2>Filter by Status</h2>
        <div class="status-buttons">
            <a href="index.php?filter=akan_tayang" class="btn <?php echo (isset($_GET['filter']) && $_GET['filter'] == 'akan_tayang') ? 'btn-primary' : 'btn-secondary'; ?>">
                🎬 Akan Tayang
            </a>
            <a href="index.php?filter=sedang_tayang" class="btn <?php echo (isset($_GET['filter']) && $_GET['filter'] == 'sedang_tayang') ? 'btn-primary' : 'btn-secondary'; ?>">
                🎥 Sedang Tayang
            </a>
            <a href="index.php?filter=telah_tayang" class="btn <?php echo (isset($_GET['filter']) && $_GET['filter'] == 'telah_tayang') ? 'btn-primary' : 'btn-secondary'; ?>">
                📀 Telah Tayang
            </a>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>