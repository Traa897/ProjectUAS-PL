<?php require_once 'views/layouts/header.php'; ?>

<div class="container">
    <div class="header-section">
        <h1>📊 Dashboard Admin</h1>
        <div style="display: flex; gap: 10px;">
            <a href="index.php?module=admin&action=createFilm" class="btn btn-primary">Tambah Film</a>
            <a href="index.php?module=jadwal&action=create" class="btn btn-info"> Tambah Jadwal</a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">🎬</div>
            <div class="stat-info">
                <h3><?php echo $totalFilms; ?></h3>
                <p>Total Film</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">🏢</div>
            <div class="stat-info">
                <h3><?php echo $totalBioskops; ?></h3>
                <p>Total Bioskop</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">📅</div>
            <div class="stat-info">
                <h3><?php echo $totalJadwals; ?></h3>
                <p>Jadwal Tayang</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">👥</div>
            <div class="stat-info">
                <h3><?php echo $totalUsers; ?></h3>
                <p>User Aktif</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">🎫</div>
            <div class="stat-info">
                <h3><?php echo $totalTransaksi; ?></h3>
                <p>Total Transaksi</p>
            </div>
        </div>

        <div class="stat-card" style="grid-column: span 2;">
            <div class="stat-icon">💰</div>
            <div class="stat-info">
                <h3>Rp <?php echo number_format($totalRevenue, 0, ',', '.'); ?></h3>
                <p>Total Pendapatan</p>
            </div>
        </div>
    </div>

    <!-- Status Film Statistics - FIXED: Hanya 2 status -->
    <div class="section-header" style="margin-top: 40px;">
        <h2>Status Film</h2>
    </div>

    <div class="stats-grid" style="grid-template-columns: repeat(2, 1fr);">
        <?php
        require_once 'models/Film.php';
        $filmModel = new Film($this->db);
        
        // PERBAIKAN: Hanya 2 status
        $countSedangTayang = $filmModel->countByStatus('sedang_tayang');
        $countAkanTayang = $filmModel->countByStatus('akan_tayang');
        ?>
        
        <div class="stat-card" style="border-left: 5px solid #1e3a8a;">
            <div class="stat-icon">🔥</div>
            <div class="stat-info">
                <h3><?php echo $countSedangTayang; ?></h3>
                <p>Sedang Tayang</p>
            </div>
        </div>

        <div class="stat-card" style="border-left: 5px solid #f59e0b;">
            <div class="stat-icon">⚡</div>
            <div class="stat-info">
                <h3><?php echo $countAkanTayang; ?></h3>
                <p>Akan Tayang (Pre-Sale)</p>
            </div>
        </div>
    </div>

    <!-- Kelola Film Section -->
    <div class="section-header" style="margin-top: 40px;">
        <h2>Kelola Film</h2>
    </div>

    <?php if(empty($films)): ?>
        <div class="empty-state">
            <p>❌ Belum ada film dengan jadwal tayang</p>
            <a href="index.php?module=admin&action=createFilm" class="btn btn-primary"> Tambah Film Pertama</a>
        </div>
    <?php else: ?>
        <div class="movie-scroll" style="margin-bottom: 40px;">
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
                            <a href="index.php?module=film&action=show&id=<?php echo $film['id_film']; ?>" class="btn btn-info btn-sm"> Detail</a>
                            <a href="index.php?module=admin&action=editFilm&id=<?php echo $film['id_film']; ?>" class="btn btn-warning btn-sm"> Edit</a>
                            <a href="index.php?module=admin&action=deleteFilm&id=<?php echo $film['id_film']; ?>" 
                               class="btn btn-danger btn-sm" 
                               onclick="return confirm('Hapus film <?php echo htmlspecialchars($film['judul_film']); ?>?')"> Hapus</a>
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

    <!-- Top Selling Films -->
    <div class="section-header" style="margin-top: 40px;">
        <h2>🏆 Film Terlaris</h2>
    </div>

    <?php if(empty($topFilms)): ?>
        <div class="empty-state">
            <p>❌ Belum ada data penjualan film</p>
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
        <h2>🎫 Transaksi Terbaru</h2>
    </div>

    <?php if(empty($recentTransactions)): ?>
        <div class="empty-state">
            <p>❌ Belum ada transaksi</p>
        </div>
    <?php else: ?>
        <div style="display: grid; gap: 15px;">
            <?php foreach($recentTransactions as $trans): ?>
                <div style="background: white; border-radius: 10px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); display: grid; grid-template-columns: auto 1fr auto; gap: 20px; align-items: center;">
                    <div style="width: 60px; height: 60px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 24px;">
                        🎫
                    </div>
                    <div>
                        <h4 style="margin: 0 0 5px 0; color: #032541;">
                            <?php echo htmlspecialchars($trans['kode_booking']); ?>
                        </h4>
                        <p style="margin: 3px 0; color: #666; font-size: 14px;">
                            👤 <?php echo htmlspecialchars($trans['nama_user'] ?? $trans['email'] ?? 'User'); ?> • 
                            <?php echo htmlspecialchars($trans['email']); ?>
                        </p>
                        <p style="margin: 3px 0; color: #666; font-size: 14px;">
                            🕐 <?php echo date('d/m/Y H:i', strtotime($trans['tanggal_transaksi'])); ?> • 
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

    <!-- Monthly Revenue Chart -->
    <?php if(!empty($monthlyRevenue)): ?>
    <div class="section-header" style="margin-top: 40px;">
        <h2>📈 Pendapatan Bulanan (6 Bulan Terakhir)</h2>
    </div>
    
    <div style="background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
        <div style="overflow-x: auto;">
            <table style="width: 100%;">
                <thead>
                    <tr style="border-bottom: 2px solid #032541;">
                        <th style="padding: 15px; text-align: left; color: #032541;">Bulan</th>
                        <th style="padding: 15px; text-align: center; color: #032541;">Transaksi</th>
                        <th style="padding: 15px; text-align: right; color: #032541;">Pendapatan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($monthlyRevenue as $data): ?>
                        <tr style="border-bottom: 1px solid #e0e0e0;">
                            <td style="padding: 15px;">
                                <?php 
                                $bulanIndo = [
                                    '01' => 'Januari', '02' => 'Februari', '03' => 'Maret',
                                    '04' => 'April', '05' => 'Mei', '06' => 'Juni',
                                    '07' => 'Juli', '08' => 'Agustus', '09' => 'September',
                                    '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
                                ];
                                $parts = explode('-', $data['bulan']);
                                echo $bulanIndo[$parts[1]] . ' ' . $parts[0];
                                ?>
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                <span style="padding: 5px 12px; background: #f8f9fa; border-radius: 15px; font-weight: 600;">
                                    <?php echo number_format($data['jumlah_transaksi']); ?>
                                </span>
                            </td>
                            <td style="padding: 15px; text-align: right;">
                                <strong style="color: #01b4e4; font-size: 16px;">
                                    Rp <?php echo number_format($data['pendapatan'], 0, ',', '.'); ?>
                                </strong>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php require_once 'views/layouts/footer.php'; ?>