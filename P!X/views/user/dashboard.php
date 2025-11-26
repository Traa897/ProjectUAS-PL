<?php require_once 'views/layouts/header.php'; ?>

<div class="container">
    <div class="header-section">
        <h1>Profile </h1>
        <p style="margin: 10px 0 0 0; color: #666;">
            Selamat datang, <strong><?php echo htmlspecialchars($this->user->nama_lengkap); ?></strong>!
        </p>
    </div>

   
<!-- User Profile Card -->
<div style="background: #018CB6; padding: 30px; border-radius: 10px; margin-bottom: 30px; color: white;">
    <div style="display: flex; align-items: center; gap: 25px;">
        <div style="width: 100px; height: 100px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 48px;">
        👤
        </div>
        <div style="flex: 1;">
            <h2 style="margin: 0 0 10px 0;"><?php echo htmlspecialchars($this->user->nama_lengkap); ?></h2>
            <p style="margin: 5px 0; opacity: 0.9;">📧 <?php echo htmlspecialchars($this->user->email); ?></p>
            <p style="margin: 5px 0; opacity: 0.9;">👤 <?php echo htmlspecialchars($this->user->username); ?></p>
            <?php if($this->user->no_telpon): ?>
                <p style="margin: 5px 0; opacity: 0.9;">📱 <?php echo htmlspecialchars($this->user->no_telpon); ?></p>
            <?php endif; ?>
        </div>
        <a href="index.php?module=user&action=profile" class="btn btn-secondary" style="background: rgba(255,255,255,0.2); border: 2px solid white;">
             Edit 
        </a>
    </div>
</div>

    <!-- Statistics -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">🎫</div>
            <div class="stat-info">
                <h5><?php echo count($transactions); ?></h5>
                <p>Total Transaksi</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon"></div>
            <div class="stat-info">
                <h5><?php 
                    $successCount = 0;
                    foreach($transactions as $t) {
                        if($t['status_pembayaran'] == 'berhasil') $successCount++;
                    }
                    echo $successCount;
                ?></h5>
                <p>Transaksi Berhasil</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon"></div>
            <div class="stat-info">
                <h5>Rp <?php 
                    $totalSpent = 0;
                    foreach($transactions as $t) {
                        if($t['status_pembayaran'] == 'berhasil') {
                            $totalSpent += $t['total_harga'];
                        }
                    }
                    echo number_format($totalSpent, 0, ',', '.');
                ?></h5>
                <p>Total Pengeluaran</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon"></div>
            <div class="stat-info">
                <h4><?php echo date('d M Y', strtotime($this->user->tanggal_daftar)); ?></h4>
                <p>Bergabung Sejak</p>
            </div>
        </div>
    </div>

   
<!-- Quick Actions -->
<div class="section-header" style="margin-top: 40px;">
    <h2>Lainnya</h2>
</div>

<div style="display: flex; justify-content: flex-start; gap: 15px; margin-bottom: 30px;">
    <a href="index.php?module=user&action=riwayat" style="background: #02739A; padding: 10px; border-radius: 8px; color: white; text-decoration: none; text-align: center; transition: transform 0.2s; width: 150px;" onmouseover="this.style.transform='translateY(-3px)'" onmouseout="this.style.transform='translateY(0)'">
        <div style="font-size: 24px; margin-bottom: 5px;">🎫</div>
        <h3 style="margin: 0; font-size: 14px;">Riwayat Tiket</h3>
    </a>

    <a href="index.php?module=user&action=profile" style="background: #4facfe; padding: 10px; border-radius: 8px; color: white; text-decoration: none; text-align: center; transition: transform 0.2s; width: 150px;" onmouseover="this.style.transform='translateY(-3px)'" onmouseout="this.style.transform='translateY(0)'">
        <div style="font-size: 24px; margin-bottom: 5px;">⚙️</div>
        <h3 style="margin: 0; font-size: 14px;">Pengaturan</h3>
    </a>
</div>

    <!-- Recent Transactions -->
    <div class="section-header">
        <h2>Transaksi Terakhir</h2>
        <a href="index.php?module=user&action=riwayat" class="btn btn-secondary">Lihat Semua</a>
    </div>

    <?php if(empty($transactions)): ?>
        <div class="empty-state">
            <p>Belum ada transaksi</p>
         
        </div>
    <?php else: ?>
        <div style="display: grid; gap: 15px;">
            <?php 
            $recentTransactions = array_slice($transactions, 0, 5);
            foreach($recentTransactions as $trans): 
            ?>
                <div style="background: white; border-radius: 10px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); display: grid; grid-template-columns: auto 1fr auto; gap: 20px; align-items: center;">
                    <div style="width: 60px; height: 60px; background: <?php 
                        echo $trans['status_pembayaran'] === 'berhasil' ? 'linear-gradient(135deg, #21d07a, #05a85b)' : 
                            ($trans['status_pembayaran'] === 'pending' ? 'linear-gradient(135deg, #ffc107, #ff9800)' : 
                            'linear-gradient(135deg, #dc3545, #c82333)'); 
                    ?>; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 24px;">
                        <?php 
                        echo $trans['status_pembayaran'] === 'berhasil' ? '✓' : 
                            ($trans['status_pembayaran'] === 'pending' ? '⏳' : '✗'); 
                        ?>
                    </div>
                    <div>
                        <h4 style="margin: 0 0 5px 0; color: #032541; font-size: 18px;">
                            <?php echo htmlspecialchars($trans['kode_booking']); ?>
                        </h4>
                        <p style="margin: 3px 0; color: #666; font-size: 14px;">
                            🕐 <?php echo date('d/m/Y H:i', strtotime($trans['tanggal_transaksi'])); ?>
                        </p>
                        <p style="margin: 3px 0; color: #666; font-size: 14px;">
                            🎫 <?php echo $trans['jumlah_tiket']; ?> tiket • 
                            💳 <?php 
                            $metode = [
                                'transfer' => 'Transfer',
                                'e-wallet' => 'E-Wallet',
                                'kartu_kredit' => 'Kartu Kredit',
                                'tunai' => 'Tunai'
                            ];
                            echo $metode[$trans['metode_pembayaran']] ?? $trans['metode_pembayaran'];
                            ?>
                        </p>
                    </div>
                    <div style="text-align: right;">
                        <div style="color: #01b4e4; font-weight: 700; font-size: 18px; margin-bottom: 8px;">
                            Rp <?php echo number_format($trans['total_harga'], 0, ',', '.'); ?>
                        </div>
                        <span style="padding: 5px 12px; background: <?php 
                            echo $trans['status_pembayaran'] === 'berhasil' ? '#21d07a' : 
                                ($trans['status_pembayaran'] === 'pending' ? '#ffc107' : '#dc3545'); 
                        ?>; color: white; border-radius: 15px; font-size: 12px; font-weight: 600;">
                            <?php echo strtoupper($trans['status_pembayaran']); ?>
                        </span>
                        <br>
                        <a href="index.php?module=user&action=detailTiket&id=<?php echo $trans['id_transaksi']; ?>" 
                           class="btn btn-info btn-sm" style="margin-top: 10px;">
                            👁️ Detail
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once 'views/layouts/footer.php'; ?>   