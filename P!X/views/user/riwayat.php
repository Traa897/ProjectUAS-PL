<?php require_once 'views/layouts/header.php'; ?>

<div class="container">
    <div class="header-section">
        <h1>Riwayat Transaksi</h1>
        <a href="index.php?module=user&action=dashboard" class="btn btn-secondary">Kembali</a>
    </div>

    <?php if(empty($transactions)): ?>
        <div class="empty-state">
            <p>Belum ada riwayat transaksi</p>
            <a href="index.php?module=film" class="btn btn-primary">Booking</a>
        </div>
    <?php else: ?>
        <div style="display: grid; gap: 20px;">
            <?php foreach($transactions as $trans): ?>
                <div style="background: white; border-radius: 10px; padding: 25px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                    <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 20px;">
                        <div>
                            <h3 style="margin: 0 0 10px 0; color: #032541; font-size: 22px;">
                                <?php echo htmlspecialchars($trans['kode_booking']); ?>
                            </h3>
                            <p style="margin: 5px 0; color: #666;">
                             <?php echo date('d F Y, H:i', strtotime($trans['tanggal_transaksi'])); ?>
                            </p>
                        </div>
                        <span style="padding: 8px 20px; background: <?php 
                            echo $trans['status_pembayaran'] === 'berhasil' ? '#21d07a' : 
                                ($trans['status_pembayaran'] === 'pending' ? '#ffc107' : '#dc3545'); 
                        ?>; color: white; border-radius: 20px; font-weight: 600;">
                            <?php echo strtoupper($trans['status_pembayaran']); ?>
                        </span>
                    </div>

                    <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
                            <div>
                                <strong style="color: #666; font-size: 14px;">Jumlah Tiket:</strong>
                                <p style="margin: 5px 0 0 0; color: #032541; font-size: 18px; font-weight: 600;">
                                     <?php echo $trans['jumlah_tiket']; ?> tiket
                                </p>
                            </div>
                            <div>
                                <strong style="color: #666; font-size: 14px;">Total Harga:</strong>
                                <p style="margin: 5px 0 0 0; color: #01b4e4; font-size: 18px; font-weight: 700;">
                                    Rp <?php echo number_format($trans['total_harga'], 0, ',', '.'); ?>
                                </p>
                            </div>
                            <div>
                                <strong style="color: #666; font-size: 14px;">Metode Pembayaran:</strong>
                                <p style="margin: 5px 0 0 0; color: #032541; font-size: 16px; font-weight: 600;">
                                    <?php 
                                    $metode = [
                                        'transfer' => '🏦 Transfer Bank',
                                        'e-wallet' => '📱 E-Wallet',
                                        'kartu_kredit' => '💳 Kartu Kredit',
                                        'tunai' => '💵 Tunai'
                                    ];
                                    echo $metode[$trans['metode_pembayaran']] ?? $trans['metode_pembayaran'];
                                    ?>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div style="display: flex; gap: 10px;">
                        <a href="index.php?module=user&action=detailTiket&id=<?php echo $trans['id_transaksi']; ?>" 
                           class="btn btn-primary">
                            Lihat Detail Tiket
                        </a>
                        <?php if($trans['status_pembayaran'] === 'berhasil'): ?>
                            <a href="index.php?module=transaksi&action=konfirmasi&kode=<?php echo $trans['kode_booking']; ?>" 
                               class="btn btn-info">
                                 Cetak Tiket
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once 'views/layouts/footer.php'; ?>