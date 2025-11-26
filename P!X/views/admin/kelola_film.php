<?php require_once 'views/layouts/header.php'; ?>

<div class="container">
    <div class="header-section">
        <h1>📊 Laporan Penjualan Film</h1>
        <a href="index.php?module=admin&action=dashboard" class="btn btn-secondary">⬅️ Kembali</a>
    </div>

    <!-- Filter Form -->
    <div style="background: white; padding: 25px; border-radius: 10px; margin-bottom: 30px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
        <form method="GET" action="index.php" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; align-items: end;">
            <input type="hidden" name="module" value="admin">
            <input type="hidden" name="action" value="laporan">
            
            <div class="form-group" style="margin: 0;">
                <label>Tanggal Mulai:</label>
                <input type="date" name="start_date" 
                       value="<?php echo htmlspecialchars($startDate); ?>"
                       style="width: 100%; padding: 10px; border: 2px solid #e0e0e0; border-radius: 5px;">
            </div>

            <div class="form-group" style="margin: 0;">
                <label>Tanggal Akhir:</label>
                <input type="date" name="end_date" 
                       value="<?php echo htmlspecialchars($endDate); ?>"
                       style="width: 100%; padding: 10px; border: 2px solid #e0e0e0; border-radius: 5px;">
            </div>

            <div class="form-group" style="margin: 0;">
                <label>Periode:</label>
                <select name="filter" style="width: 100%; padding: 10px; border: 2px solid #e0e0e0; border-radius: 5px;">
                    <option value="all" <?php echo $filter === 'all' ? 'selected' : ''; ?>>Semua Waktu</option>
                    <option value="custom" <?php echo $filter === 'custom' ? 'selected' : ''; ?>>Custom</option>
                </select>
            </div>

            <div>
                <button type="submit" class="btn btn-primary" style="width: 100%;">🔍 Filter</button>
            </div>
        </form>
    </div>

    <!-- Income Summary -->
    <div class="stats-grid" style="margin-bottom: 30px;">
        <div class="stat-card">
            <div class="stat-icon">💰</div>
            <div class="stat-info">
                <h3>Rp <?php echo number_format($income['total_income'] ?? 0, 0, ',', '.'); ?></h3>
                <p>Total Pendapatan</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">🎫</div>
            <div class="stat-info">
                <h3><?php echo number_format($income['total_tiket'] ?? 0); ?></h3>
                <p>Total Tiket Terjual</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">📋</div>
            <div class="stat-info">
                <h3><?php echo number_format($income['total_transaksi'] ?? 0); ?></h3>
                <p>Total Transaksi</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">📊</div>
            <div class="stat-info">
                <h3>Rp <?php echo $income['total_tiket'] > 0 ? number_format($income['total_income'] / $income['total_tiket'], 0, ',', '.') : 0; ?></h3>
                <p>Rata-rata per Tiket</p>
            </div>
        </div>
    </div>

    <!-- Films Table -->
    <div class="section-header">
        <h2>🎬 Data Penjualan Film</h2>
        <button onclick="window.print()" class="btn btn-info">🖨️ Cetak Laporan</button>
    </div>

    <?php if(empty($films)): ?>
        <div class="empty-state">
            <p>Tidak ada data penjualan untuk periode ini</p>
        </div>
    <?php else: ?>
        <div style="overflow-x: auto;">
            <table style="width: 100%; background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                <thead style="background: #032541; color: white;">
                    <tr>
                        <th style="padding: 15px; text-align: left;">No</th>
                        <th style="padding: 15px; text-align: left;">Film</th>
                        <th style="padding: 15px; text-align: center;">Tahun</th>
                        <th style="padding: 15px; text-align: center;">Transaksi</th>
                        <th style="padding: 15px; text-align: center;">Tiket Terjual</th>
                        <th style="padding: 15px; text-align: right;">Total Pendapatan</th>
                        <th style="padding: 15px; text-align: right;">Rata-rata Harga</th>
                        <th style="padding: 15px; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($films as $index => $film): ?>
                        <tr style="border-bottom: 1px solid #e0e0e0;">
                            <td style="padding: 15px;"><?php echo $index + 1; ?></td>
                            <td style="padding: 15px;">
                                <div style="display: flex; align-items: center; gap: 15px;">
                                    <img src="<?php echo htmlspecialchars($film['poster_url'] ?? 'https://via.placeholder.com/50x75'); ?>" 
                                         alt="<?php echo htmlspecialchars($film['judul_film']); ?>"
                                         style="width: 50px; height: 75px; object-fit: cover; border-radius: 5px;">
                                    <strong style="color: #032541;">
                                        <?php echo htmlspecialchars($film['judul_film']); ?>
                                    </strong>
                                </div>
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                <?php echo $film['tahun_rilis']; ?>
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                <span style="padding: 5px 12px; background: #f8f9fa; border-radius: 15px; font-weight: 600;">
                                    <?php echo number_format($film['total_transaksi']); ?>
                                </span>
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                <span style="padding: 8px 15px; background: #01b4e4; color: white; border-radius: 20px; font-weight: 600;">
                                    <?php echo number_format($film['total_tiket']); ?>
                                </span>
                            </td>
                            <td style="padding: 15px; text-align: right;">
                                <strong style="color: #01b4e4; font-size: 16px;">
                                    Rp <?php echo number_format($film['total_pendapatan'], 0, ',', '.'); ?>
                                </strong>
                            </td>
                            <td style="padding: 15px; text-align: right;">
                                <span style="color: #666;">
                                    Rp <?php echo number_format($film['rata_rata_harga'], 0, ',', '.'); ?>
                                </span>
                            </td>
                            <td style="padding: 15px; text-align: center;">
                                <a href="index.php?module=admin&action=detailPenjualan&id_film=<?php echo $film['id_film']; ?>" 
                                   class="btn btn-info btn-sm">Detail</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot style="background: #f8f9fa; font-weight: 700;">
                    <tr>
                        <td colspan="4" style="padding: 15px; text-align: right;">TOTAL:</td>
                        <td style="padding: 15px; text-align: center;">
                            <span style="padding: 8px 15px; background: #032541; color: white; border-radius: 20px;">
                                <?php 
                                $totalTiket = array_sum(array_column($films, 'total_tiket'));
                                echo number_format($totalTiket); 
                                ?>
                            </span>
                        </td>
                        <td style="padding: 15px; text-align: right;">
                            <strong style="color: #01b4e4; font-size: 18px;">
                                Rp <?php 
                                $totalPendapatan = array_sum(array_column($films, 'total_pendapatan'));
                                echo number_format($totalPendapatan, 0, ',', '.'); 
                                ?>
                            </strong>
                        </td>
                        <td colspan="2"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    <?php endif; ?>
</div>

<style>
@media print {
    .navbar, .footer, .btn, form {
        display: none !important;
    }
    body {
        background: white;
    }
    .container {
        max-width: 100%;
        padding: 20px;
    }
    table {
        page-break-inside: auto;
    }
    tr {
        page-break-inside: avoid;
        page-break-after: auto;
    }
}
</style>

<?php require_once 'views/layouts/footer.php'; ?>