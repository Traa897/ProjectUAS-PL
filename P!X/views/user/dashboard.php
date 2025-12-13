<?php require_once 'views/layouts/header.php'; ?>

<div class="container">
    <div class="header-section">
        <h1>Profile</h1>
        <p style="margin: 10px 0 0 0; color: #666;">
            Selamat datang, <strong><?php echo htmlspecialchars($this->user->nama_lengkap); ?></strong>!
        </p>
    </div>

   
<!-- User Profile Card -->
<div style="background: linear-gradient(135deg, #018CB6 0%, #0281AA 100%); padding: 30px; border-radius: 10px; margin-bottom: 30px; color: white; box-shadow: 0 8px 24px rgba(1, 140, 182, 0.3);">
    <div style="display: flex; align-items: center; gap: 25px;">
        <div style="width: 100px; height: 100px; background: rgba(255,255,255,0.15); border-radius: 50%; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(10px); border: 3px solid rgba(255,255,255,0.3);">
            <svg width="50" height="50" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                <circle cx="12" cy="7" r="4"></circle>
            </svg>
        </div>
        <div style="flex: 1;">
            <h2 style="margin: 0 0 10px 0;"><?php echo htmlspecialchars($this->user->nama_lengkap); ?></h2>
            <p style="margin: 5px 0; opacity: 0.9; display: flex; align-items: center; gap: 8px;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                    <polyline points="22,6 12,13 2,6"></polyline>
                </svg>
                <?php echo htmlspecialchars($this->user->email); ?>
            </p>
            <p style="margin: 5px 0; opacity: 0.9; display: flex; align-items: center; gap: 8px;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                </svg>
                <?php echo htmlspecialchars($this->user->username); ?>
            </p>
            <?php if($this->user->no_telpon): ?>
                <p style="margin: 5px 0; opacity: 0.9; display: flex; align-items: center; gap: 8px;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                    </svg>
                    <?php echo htmlspecialchars($this->user->no_telpon); ?>
                </p>
            <?php endif; ?>
        </div>
       
    </div>
</div>

    <!-- Statistics -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon" style="color: #01b4e4;">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                    <line x1="1" y1="10" x2="23" y2="10"></line>
                </svg>
            </div>
            <div class="stat-info">
                <h3><?php echo count($transactions); ?></h3>
                <p>Total Transaksi</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="color: #21d07a;">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="9 11 12 14 22 4"></polyline>
                    <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
                </svg>
            </div>
            <div class="stat-info">
                <h3><?php 
                    $successCount = 0;
                    foreach($transactions as $t) {
                        if($t['status_pembayaran'] == 'berhasil') $successCount++;
                    }
                    echo $successCount;
                ?></h3>
                <p>Transaksi Berhasil</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="color: #f5576c;">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="12" y1="1" x2="12" y2="23"></line>
                    <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                </svg>
            </div>
            <div class="stat-info">
                <h3>Rp <?php 
                    $totalSpent = 0;
                    foreach($transactions as $t) {
                        if($t['status_pembayaran'] == 'berhasil') {
                            $totalSpent += $t['total_harga'];
                        }
                    }
                    echo number_format($totalSpent, 0, ',', '.');
                ?></h3>
                <p>Total Pengeluaran</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="color: #026B91;">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="16" y1="2" x2="16" y2="6"></line>
                    <line x1="8" y1="2" x2="8" y2="6"></line>
                    <line x1="3" y1="10" x2="21" y2="10"></line>
                </svg>
            </div>
            <div class="stat-info">
                <h3><?php echo date('d M Y', strtotime($this->user->tanggal_daftar)); ?></h3>
                <p>Bergabung Sejak</p>
            </div>
        </div>
    </div>

   
<!-- Quick Actions -->
<div class="section-header" style="margin-top: 20px;">
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-bottom: 30px;">
    <a href="index.php?module=user&action=riwayat" style="background: linear-gradient(135deg, #026B91 0%, #026B91 100%); padding: 20px; border-radius: 10px; color: white; text-decoration: none; text-align: center; transition: transform 0.3s; box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
        <div style="width: 50px; height: 50px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px; backdrop-filter: blur(10px);">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                <polyline points="14 2 14 8 20 8"></polyline>
                <line x1="16" y1="13" x2="8" y2="13"></line>
                <line x1="16" y1="17" x2="8" y2="17"></line>
                <polyline points="10 9 9 9 8 9"></polyline>
            </svg>
        </div>
        <div style="font-size: 16px; font-weight: 600;">Riwayat Tiket</div>
        <div style="font-size: 12px; opacity: 0.9; margin-top: 5px;">Lihat semua transaksi</div>
    </a>

    <a href="index.php?module=user&action=profile" style="background: linear-gradient(135deg, #026B91 0%, #026B91 100%); padding: 20px; border-radius: 10px; color: white; text-decoration: none; text-align: center; transition: transform 0.3s; box-shadow: 0 4px 12px rgba(240, 147, 251, 0.3);" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
        <div style="width: 50px; height: 50px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px; backdrop-filter: blur(10px);">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                <circle cx="12" cy="7" r="4"></circle>
            </svg>
        </div>
        <div style="font-size: 16px; font-weight: 600;">Akun </div>
        <div style="font-size: 12px; opacity: 0.9; margin-top: 5px;">Perbarui data diri</div>
    </a>

    <a href="index.php?module=film" style="background: linear-gradient(135deg, #026B91 0%, #026B91 100%); padding: 20px; border-radius: 10px; color: white; text-decoration: none; text-align: center; transition: transform 0.3s; box-shadow: 0 4px 12px rgba(79, 172, 254, 0.3);" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
        <div style="width: 50px; height: 50px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px; backdrop-filter: blur(10px);">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                <rect x="2" y="7" width="20" height="15" rx="2" ry="2"></rect>
                <polyline points="17 2 12 7 7 2"></polyline>
            </svg>
        </div>
        <div style="font-size: 16px; font-weight: 600;">Lihat Film</div>
        <div style="font-size: 12px; opacity: 0.9; margin-top: 5px;">Booking tiket baru</div>
    </a>
</div>

    <!-- Recent Transactions -->
    <div class="section-header">
        <h2>Transaksi Terakhir</h2>
        <a href="index.php?module=user&action=riwayat" class="btn btn-secondary">Lihat Semua</a>
    </div>

    <?php if(empty($transactions)): ?>
        <div class="empty-state">
            <div style="width: 80px; height: 80px; background: #e0e0e0; border-radius: 50%; margin: 0 auto 20px; display: flex; align-items: center; justify-content: center;">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#999" stroke-width="2">
                    <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                    <line x1="1" y1="10" x2="23" y2="10"></line>
                </svg>
            </div>
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
                    ?>; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 24px; box-shadow: 0 4px 12px rgba(0,0,0,0.2);">
                        <?php if($trans['status_pembayaran'] === 'berhasil'): ?>
                            <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                        <?php elseif($trans['status_pembayaran'] === 'pending'): ?>
                            <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                                <circle cx="12" cy="12" r="10"></circle>
                                <polyline points="12 6 12 12 16 14"></polyline>
                            </svg>
                        <?php else: ?>
                            <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3">
                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                <line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                        <?php endif; ?>
                    </div>
                    <div>
                        <h4 style="margin: 0 0 5px 0; color: #032541; font-size: 18px;">
                            <?php echo htmlspecialchars($trans['kode_booking']); ?>
                        </h4>
                        <p style="margin: 3px 0; color: #666; font-size: 14px; display: flex; align-items: center; gap: 6px;">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"></circle>
                                <polyline points="12 6 12 12 16 14"></polyline>
                            </svg>
                            <?php echo date('d/m/Y H:i', strtotime($trans['tanggal_transaksi'])); ?>
                        </p>
                        <p style="margin: 3px 0; color: #666; font-size: 14px; display: flex; align-items: center; gap: 6px;">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                                <line x1="1" y1="10" x2="23" y2="10"></line>
                            </svg>
                            <?php echo $trans['jumlah_tiket']; ?> tiket • 
                            <?php 
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
                            Detail
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once 'views/layouts/footer.php'; ?>