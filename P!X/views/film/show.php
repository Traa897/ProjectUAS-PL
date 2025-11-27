<?php require_once 'views/layouts/header.php'; ?>

<div class="container">
    <div class="header-section">
        <h1>🎬 Detail Film</h1>
        <a href="index.php?module=film" class="btn btn-secondary">⬅️ Kembali</a>
    </div>

    <div class="detail-container">
        <div class="detail-poster">
            <img src="<?php echo htmlspecialchars($filmData['poster_url'] ?? 'https://via.placeholder.com/350x525'); ?>" 
                 alt="<?php echo htmlspecialchars($filmData['judul_film']); ?>">
            
            <div class="rating-badge-large">
                <svg viewBox="0 0 36 36" class="rating-circle-large">
                    <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                        fill="none" stroke="#204529" stroke-width="3"/>
                    <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                        fill="none" stroke="#21d07a" stroke-width="3"
                        stroke-dasharray="<?php echo ($filmData['rating'] * 10) . ', 100'; ?>"/>
                </svg>
                <span class="rating-text"><?php echo number_format($filmData['rating'] * 10, 0); ?></span>
            </div>
        </div>
        
        <div class="detail-info">
            <h2><?php echo htmlspecialchars($filmData['judul_film']); ?></h2>

            <div class="info-grid">
                <div class="info-item">
                    <strong>🎭 Genre:</strong>
                    <p><?php echo htmlspecialchars($filmData['nama_genre']); ?></p>
                </div>

                <div class="info-item">
                    <strong>📅 Tahun Rilis:</strong>
                    <p><?php echo $filmData['tahun_rilis']; ?></p>
                </div>

                <div class="info-item">
                    <strong>⏱️ Durasi:</strong>
                    <p><?php echo $filmData['durasi_menit']; ?> menit</p>
                </div>

                <div class="info-item">
                    <strong>⭐ Rating:</strong>
                    <p><?php echo $filmData['rating']; ?> / 10</p>
                </div>
            </div>

            <div class="synopsis-section">
                <strong>📖 Sinopsis:</strong>
                <p><?php echo nl2br(htmlspecialchars($filmData['sipnosis'] ?? 'Tidak ada sinopsis')); ?></p>
            </div>

            <div class="detail-actions">
                <?php 
                if(session_status() == PHP_SESSION_NONE) session_start();
                
                // Button untuk User - Booking Tiket
                if(isset($_SESSION['user_id'])): ?>
                    <a href="index.php?module=transaksi&action=pilihJadwal&id_film=<?php echo $filmData['id_film']; ?>" 
                       class="btn btn-primary" style="font-size: 18px; padding: 15px 30px;">
                        🎫 Booking Tiket
                    </a>
                <?php elseif(!isset($_SESSION['user_id']) && !isset($_SESSION['admin_id'])): ?>
                    <a href="index.php?module=auth&action=index" 
                       class="btn btn-primary" style="font-size: 18px; padding: 15px 30px;">
                        🎫 Login untuk Booking
                    </a>
                <?php endif; ?>
                
                <?php // Button untuk Admin - Edit & Delete
                if(isset($_SESSION['admin_id'])): ?>
                    <a href="index.php?module=admin&action=editFilm&id=<?php echo $filmData['id_film']; ?>" 
                       class="btn btn-warning">✏️ Edit Film</a>
                    <a href="index.php?module=admin&action=deleteFilm&id=<?php echo $filmData['id_film']; ?>" 
                       class="btn btn-danger" 
                       onclick="return confirm('Apakah Anda yakin ingin menghapus film ini?')">🗑️ Hapus Film</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>