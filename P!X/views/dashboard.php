<?php 
require_once 'views/layouts/header.php'; 
require_once 'models/QueryBuilder.php';
?>

<div class="container">
    <div class="header-section">
        <h1>Dashboard P!X</h1>
    </div>

    <div class="stats-grid">
        <?php
        $qb = new QueryBuilder($this->db);
        
        // Statistics
        $totalFilms = $qb->reset()->table('Film')->count();
        $totalAktors = $qb->reset()->table('Aktor')->count();
        $totalBioskops = $qb->reset()->table('Bioskop')->count();
        $totalGenres = $qb->reset()->table('Genre')->count();
        $totalJadwals = $qb->reset()->table('Jadwal_Tayang')->count();
        $avgRating = $qb->reset()->table('Film')->avg('rating');
        ?>

        <div class="stat-card">
            <div class="stat-info">
                <h3><?php echo $totalFilms; ?></h3>
                <p>Total Film</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-info">
                <h3><?php echo $totalBioskops; ?></h3>
                <p>Total Bioskop</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-info">
                <h3><?php echo $totalJadwals; ?></h3>
                <p>Jadwal Tayang</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-info">
                <h3><?php echo number_format($avgRating, 1); ?></h3>
                <p>Rating Rata-rata</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-info">
                <h3><?php echo $totalGenres; ?></h3>
                <p>Genre Film</p>
            </div>
        </div>
    </div>

    <!-- Film Rating Tertinggi -->
    <div class="section-header" style="margin-top: 40px;">
        <h2>Film Rating Tertinggi</h2>
        <a href="index.php?module=film" class="btn btn-secondary">Lihat Semua</a>
    </div>

    <?php
    $topFilms = $this->film->getTopRated(5)->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <div class="movie-scroll">
        <?php if(empty($topFilms)): ?>
            <div class="empty-state">
                <p>Belum ada film</p>
            </div>
        <?php else: ?>
            <?php foreach($topFilms as $film): ?>
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
                    </div>
                    <div class="movie-info-scroll">
                        <h3><?php echo htmlspecialchars($film['judul_film']); ?></h3>
                        <p class="movie-date"><?php echo $film['tahun_rilis']; ?> • <?php echo $film['durasi_menit']; ?> menit</p>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Genre Distribution -->
    <div class="section-header" style="margin-top: 40px;">
        <h2>Distribusi Film per Genre</h2>
    </div>

    <?php
    $genreStats = $this->genre->countMovies()->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <div class="stats-grid">
        <?php foreach($genreStats as $genre): ?>
            <div class="stat-card">
                <div class="stat-icon"></div>
                <div class="stat-info">
                    <h3><?php echo $genre['total_film']; ?></h3>
                    <p><?php echo htmlspecialchars($genre['nama_genre']); ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>