<?php 
// views/dashboard.php
// REPLACE FILE INI DENGAN KODE DI BAWAH

require_once 'views/layouts/header.php'; 
?>

<div class="container">
    <div class="header-section">
        <h1>Dashboard P!X</h1>
    </div>

    <div class="stats-grid">
        <?php
        // Menggunakan Query Builder untuk semua statistik
        require_once 'models/QueryBuilder.php';
        $qb = new QueryBuilder($this->db);
        
        // Total Movies menggunakan Query Builder
        $totalMovies = $qb->reset()->table('movies')->count();
        
        // Movies per status menggunakan Query Builder
        $akanTayang = $qb->reset()->table('movies')->where('status', '=', 'akan_tayang')->count();
        $sedangTayang = $qb->reset()->table('movies')->where('status', '=', 'sedang_tayang')->count();
        $telahTayang = $qb->reset()->table('movies')->where('status', '=', 'telah_tayang')->count();
        
        // Average rating menggunakan Query Builder
        $avgRating = $qb->reset()->table('movies')->avg('rating');
        
        // Total Genres menggunakan Query Builder
        $totalGenres = $qb->reset()->table('genres')->count();
        ?>

        <div class="stat-card">
            <div class="stat-icon">🎬</div>
            <div class="stat-info">
                <h3><?php echo $totalMovies; ?></h3>
                <p>Total Film</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">📅</div>
            <div class="stat-info">
                <h3><?php echo $akanTayang; ?></h3>
                <p>Akan Tayang</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">🎥</div>
            <div class="stat-info">
                <h3><?php echo $sedangTayang; ?></h3>
                <p>Sedang Tayang</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">📀</div>
            <div class="stat-info">
                <h3><?php echo $telahTayang; ?></h3>
                <p>Telah Tayang</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">⭐</div>
            <div class="stat-info">
                <h3><?php echo number_format($avgRating, 1); ?></h3>
                <p>Rating Rata-rata</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">🎭</div>
            <div class="stat-info">
                <h3><?php echo $totalGenres; ?></h3>
                <p>Total Genre</p>
            </div>
        </div>
    </div>

    <div class="section-header" style="margin-top: 40px;">
        <h2>🆕 Film Terbaru</h2>
        <a href="index.php" class="btn btn-secondary">Lihat Semua</a>
    </div>

    <?php
    // Menggunakan method getLatest dari Movie model dengan Query Builder
    $latestMovies = $this->movie->getLatest(5)->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <div class="movie-scroll">
        <?php if(empty($latestMovies)): ?>
            <div class="empty-state">
                <p>Belum ada film</p>
            </div>
        <?php else: ?>
            <?php foreach($latestMovies as $movie): ?>
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
                    </div>
                    <div class="movie-info-scroll">
                        <h3><?php echo htmlspecialchars($movie['title']); ?></h3>
                        <p class="movie-date"><?php echo date('M d, Y', strtotime($movie['release_date'])); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <div class="section-header" style="margin-top: 40px;">
        <h2>⭐ Film Rating Tertinggi</h2>
    </div>

    <?php
    // Menggunakan method getTopRated dari Movie model dengan Query Builder
    $topRatedMovies = $this->movie->getTopRated(5)->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <div class="movie-scroll">
        <?php if(empty($topRatedMovies)): ?>
            <div class="empty-state">
                <p>Belum ada film</p>
            </div>
        <?php else: ?>
            <?php foreach($topRatedMovies as $movie): ?>
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
                    </div>
                    <div class="movie-info-scroll">
                        <h3><?php echo htmlspecialchars($movie['title']); ?></h3>
                        <p class="movie-date"><?php echo date('M d, Y', strtotime($movie['release_date'])); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>