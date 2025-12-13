<?php require_once 'views/layouts/header.php'; ?>

<div class="container">
    <div class="header-section">
        <h1>Dashboard Admin</h1>
        <div style="display: flex; gap: 10px;">
            <a href="index.php?module=admin&action=createFilm" class="btn btn-primary">Tambah Film</a>
            <a href="index.php?module=jadwal&action=create" class="btn btn-info">Tambah Jadwal</a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="2" y="7" width="20" height="15" rx="2" ry="2"></rect>
                    <polyline points="17 2 12 7 7 2"></polyline>
                </svg>
            </div>
            <div class="stat-info">
                <h3><?php echo $totalFilms; ?></h3>
                <p>Total Film</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                    <circle cx="12" cy="10" r="3"></circle>
                </svg>
            </div>
            <div class="stat-info">
                <h3><?php echo $totalBioskops; ?></h3>
                <p>Total Bioskop</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="16" y1="2" x2="16" y2="6"></line>
                    <line x1="8" y1="2" x2="8" y2="6"></line>
                    <line x1="3" y1="10" x2="21" y2="10"></line>
                </svg>
            </div>
            <div class="stat-info">
                <h3><?php echo $totalJadwals; ?></h3>
                <p>Jadwal Tayang</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                </svg>
            </div>
            <div class="stat-info">
                <h3><?php echo $totalUsers; ?></h3>
                <p>User Aktif</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                </svg>
            </div>
            <div class="stat-info">
                <h3><?php echo $totalTransaksi; ?></h3>
                <p>Total Transaksi</p>
            </div>
        </div>

        <div class="stat-card" style="grid-column: span 2;">
            <div class="stat-icon">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="12" y1="1" x2="12" y2="23"></line>
                    <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                </svg>
            </div>
            <div class="stat-info">
                <h3>Rp <?php echo number_format($totalRevenue, 0, ',', '.'); ?></h3>
                <p>Total Pendapatan</p>
            </div>
        </div>
    </div>

    <!-- Film Baru (Belum Ada Jadwal) -->
    <div class="section-header" style="margin-top: 40px;">
        <h2>Kelola Film</h2>
    </div>

    <?php
    $query_new_films = "SELECT f.*, g.nama_genre 
                        FROM Film f
                        LEFT JOIN Genre g ON f.id_genre = g.id_genre
                        WHERE NOT EXISTS (
                            SELECT 1 FROM Jadwal_Tayang jt 
                            WHERE jt.id_film = f.id_film
                        )
                        ORDER BY f.created_at DESC
                        LIMIT 10";
    $stmt_new_films = $this->db->prepare($query_new_films);
    $stmt_new_films->execute();
    $newFilms = $stmt_new_films->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <?php if(empty($newFilms)): ?>
        <div style="background: #f8f9fa; padding: 40px; border-radius: 10px; text-align: center;">
            <div style="width: 60px; height: 60px; background: #e0e0e0; border-radius: 50%; margin: 0 auto 15px; display: flex; align-items: center; justify-content: center;">
                <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="#999" stroke-width="2">
                    <polyline points="9 11 12 14 22 4"></polyline>
                    <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                </svg>
            </div>
            <p style="color: #666; margin: 0;">Semua film sudah memiliki jadwal tayang</p>
        </div>
    <?php else: ?>
        <div class="movie-scroll" style="margin-bottom: 40px;">
            <?php foreach($newFilms as $film): ?>
                <div class="movie-card-scroll">
                    <div class="movie-poster-scroll">
                        <img src="<?php echo htmlspecialchars($film['poster_url'] ?? 'https://via.placeholder.com/150x225'); ?>" 
                             alt="<?php echo htmlspecialchars($film['judul_film']); ?>">
                        
                        <div style="position: absolute; top: 8px; left: 8px; background: linear-gradient(135deg, #6c757d, #5a6268); color: white; padding: 5px 12px; border-radius: 20px; font-size: 11px; font-weight: 700; box-shadow: 0 2px 8px rgba(0,0,0,0.3); text-transform: uppercase; letter-spacing: 0.5px;">
                            BELUM ADA JADWAL
                        </div>
                        
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
                            <a href="index.php?module=film&action=show&id=<?php echo $film['id_film']; ?>" class="btn btn-info btn-sm" style="width: 100%;">Detail Film</a>
                        </div>
                    </div>
                    <div class="movie-info-scroll">
                        <h3><?php echo htmlspecialchars($film['judul_film']); ?></h3>
                        <p class="movie-date"><?php echo $film['tahun_rilis']; ?> • <?php echo $film['durasi_menit']; ?> menit</p>
                        <p style="font-size: 12px; color: #01b4e4;">
                            <?php echo htmlspecialchars($film['nama_genre'] ?? 'No Genre'); ?>
                        </p>
                        <p style="font-size: 11px; color: #dc3545; margin-top: 5px; font-weight: 600;">
                            Perlu ditambahkan jadwal
                        </p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- Top Selling Films -->
    <div class="section-header" style="margin-top: 40px;">
        <h2>Film Terlaris</h2>
    </div>

    <?php if(empty($topFilms)): ?>
        <div style="background: #f8f9fa; padding: 40px; border-radius: 10px; text-align: center;">
            <p style="color: #666; margin: 0;">Belum ada data penjualan film</p>
        </div>
    <?php else: ?>
        <div style="overflow-x: auto;">
            <table style="width: 100%; background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                <thead style="background: #032541; color: white;">
                    <tr>
                        <th style="padding: 15px; text-align: left;">Rank</th>
                        <th style="padding: 15px; text-align: left;">Film</th>
                        <th style="padding: 15px; text-align: center;">Tiket Terjual</th>
                        <th style="padding: 15px; text-align: right;">Pendapatan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($topFilms as $index => $film): ?>
                        <tr style="border-bottom: 1px solid #e0e0e0;">
                            <td style="padding: 15px;">
                                <div style="width: 40px; height: 40px; background: <?php echo $index < 3 ? 'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)' : '#f8f9fa'; ?>; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; color: <?php echo $index < 3 ? 'white' : '#666'; ?>;">
                                    <?php echo $index + 1; ?>
                                </div>
                            </td>
                            <td style="padding: 15px;">
                                <div style="display: flex; align-items: center; gap: 15px;">
                                    <img src="<?php echo htmlspecialchars($film['poster_url'] ?? 'https://via.placeholder.com/50x75'); ?>" 
                                         alt="<?php echo htmlspecialchars($film['judul_film']); ?>"
                                         style="width: 50px; height: 75px; object-fit: cover; border-radius: 5px;">
                                    <strong style="color: #032541; font-size: 16px;">
                                        <?php echo htmlspecialchars($film['judul_film']); ?>
                                    </strong>
                                </div>
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                <span style="padding: 8px 15px; background: #01b4e4; color: white; border-radius: 20px; font-weight: 600;">
                                    <?php echo number_format($film['total_tiket']); ?> tiket
                                </span>
                            </td>
                            <td style="padding: 15px; text-align: right;">
                                <strong style="color: #01b4e4; font-size: 18px;">
                                    Rp <?php echo number_format($film['total_pendapatan'], 0, ',', '.'); ?>
                                </strong>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

    <!-- Recent Transactions -->
    <div class="section-header" style="margin-top: 40px;">
        <h2>Transaksi Terbaru</h2>
    </div>

    <?php if(empty($recentTransactions)): ?>
        <div style="background: #f8f9fa; padding: 40px; border-radius: 10px; text-align: center;">
            <p style="color: #666; margin: 0;">Belum ada transaksi</p>
        </div>
    <?php else: ?>
        <div style="display: grid; gap: 15px;">
            <?php foreach($recentTransactions as $trans): ?>
                <div style="background: white; border-radius: 10px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); display: grid; grid-template-columns: auto 1fr auto; gap: 20px; align-items: center;">
                    <div style="width: 60px; height: 60px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 24px;">
                        <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                    </div>
                    <div>
                        <h4 style="margin: 0 0 5px 0; color: #032541;">
                            <?php echo htmlspecialchars($trans['kode_booking']); ?>
                        </h4>
                        <p style="margin: 3px 0; color: #666; font-size: 14px;">
                            <?php echo htmlspecialchars($trans['nama_user'] ?? $trans['email'] ?? 'User'); ?> • 
                            <?php echo htmlspecialchars($trans['email']); ?>
                        </p>
                        <p style="margin: 3px 0; color: #666; font-size: 14px;">
                            <?php echo date('d/m/Y H:i', strtotime($trans['tanggal_transaksi'])); ?> • 
                            <?php echo $trans['jumlah_tiket']; ?> tiket
                        </p>
                    </div>
                    <div style="text-align: right;">
                        <div style="color: #01b4e4; font-weight: 700; font-size: 18px; margin-bottom: 5px;">
                            Rp <?php echo number_format($trans['total_harga'], 0, ',', '.'); ?>
                        </div>
                        <span style="padding: 5px 12px; background: <?php 
                            echo $trans['status_pembayaran'] === 'berhasil' ? '#21d07a' : 
                                ($trans['status_pembayaran'] === 'pending' ? '#ffc107' : '#dc3545'); 
                        ?>; color: white; border-radius: 15px; font-size: 12px; font-weight: 600;">
                            <?php echo strtoupper($trans['status_pembayaran']); ?>
                        </span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once 'views/layouts/footer.php'; ?>