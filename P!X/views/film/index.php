<?php require_once 'views/layouts/header.php'; ?>

<!-- Hero Section -->
<section class="hero-section-films">
    <div class="hero-content-films">
        <h1>Daftar Film</h1>
        <p>Koleksi Film Terbaik Untuk Anda</p>
        
        <!-- Search Bar -->
        <form method="GET" action="index.php" class="hero-search-films">
            <input type="hidden" name="module" value="film">
            <input type="text" name="search" placeholder="Cari film berdasarkan judul..." 
                   value="<?php echo htmlspecialchars($search ?? ''); ?>">
            <button type="submit" class="btn-search-films">Cari</button>
        </form>
    </div>
</section>

<div class="container">
    <!-- Filter by Genre Section -->
    <section class="filter-genre-section">
        <h2>Filter by Genre</h2>
        <div class="genre-buttons">
            <a href="index.php?module=film" class="genre-btn <?php echo ($genre_filter == '') ? 'active' : ''; ?>">
                Semua Genre
            </a>
            <?php foreach($genres as $genre): ?>
                <a href="index.php?module=film&genre=<?php echo $genre['id_genre']; ?>" 
                   class="genre-btn <?php echo ($genre_filter == $genre['id_genre']) ? 'active' : ''; ?>">
                    <?php echo htmlspecialchars($genre['nama_genre']); ?>
                </a>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Film Grid -->
    <?php if(empty($films)): ?>
        <div class="empty-state-films">
            <h3>😢 Tidak ada film ditemukan</h3>
            <p>Coba ubah filter atau kata kunci pencarian</p>
        </div>
    <?php else: ?>
        <div class="films-grid">
            <?php 
            // Map status to display text
            $statusText = [
                'sedang_tayang' => 'SEDANG TAYANG',
                'akan_tayang' => 'BELUM ADA JADWAL',
                'telah_tayang' => 'TELAH TAYANG',
                'tidak_ada_jadwal' => 'BELUM ADA JADWAL'
            ];
            
            // Map status to CSS class
            $statusClass = [
                'sedang_tayang' => 'status-now-showing',
                'akan_tayang' => 'status-no-schedule',
                'telah_tayang' => 'status-already-shown',
                'tidak_ada_jadwal' => 'status-no-schedule'
            ];
            
            foreach($films as $film): 
                $status = $film['status'] ?? 'tidak_ada_jadwal';
            ?>
                <div class="film-card">
                    <div class="film-poster">
                        <img src="<?php echo htmlspecialchars($film['poster_url'] ?? 'https://via.placeholder.com/300x450'); ?>" 
                             alt="<?php echo htmlspecialchars($film['judul_film']); ?>">
                        
                        <!-- Status Badge -->
                        <span class="status-badge <?php echo $statusClass[$status] ?? 'status-no-schedule'; ?>">
                            <?php echo $statusText[$status] ?? 'BELUM ADA JADWAL'; ?>
                        </span>
                        
                        <!-- Rating Circle -->
                        <div class="rating-circle-badge">
                            <svg width="50" height="50">
                                <circle cx="25" cy="25" r="22" fill="none" stroke="#2D3748" stroke-width="3"/>
                                <circle cx="25" cy="25" r="22" fill="none" stroke="#14B8A6" stroke-width="3"
                                        stroke-dasharray="<?php echo (($film['rating'] * 10) * 138 / 100); ?> 138"
                                        stroke-linecap="round" transform="rotate(-90 25 25)"/>
                            </svg>
                            <span class="rating-text"><?php echo number_format($film['rating'] * 10, 0); ?></span>
                        </div>
                    </div>
                    
                    <div class="film-info">
                        <h3><?php echo htmlspecialchars($film['judul_film']); ?></h3>
                        <p class="film-meta">
                            <?php echo $film['tahun_rilis']; ?> • <?php echo $film['durasi_menit']; ?> menit
                        </p>
                        <p class="film-genre">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/>
                                <line x1="4" y1="22" x2="4" y2="15"/>
                            </svg>
                            <?php echo htmlspecialchars($film['nama_genre'] ?? 'Unknown'); ?>
                        </p>
                        
                        <a href="index.php?module=film&action=show&id=<?php echo $film['id_film']; ?>" 
                           class="btn-detail">
                            Lihat Detail
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once 'views/layouts/footer.php'; ?>