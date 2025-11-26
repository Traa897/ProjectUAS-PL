<?php 
require_once 'views/layouts/header.php';
$trans = $detailTransaksi['transaksi'];
$tickets = $detailTransaksi['tickets'];
$firstTicket = $tickets[0] ?? null;
?>

<div class="container">
    <div class="header-section">
        <h1>📄 Detail Transaksi</h1>
        <a href="index.php?module=admin&action=laporanTransaksi" class="btn btn-secondary">⬅️ Kembali</a>
    </div>

    <div class="detail-container" style="grid-template-columns: 1fr;">
        <div class="detail-info">
            <!-- Status Badge -->
            <div style="text-align: center; margin-bottom: 30px;">
                <?php
                $statusColors = [
                    'berhasil' => 'linear-gradient(135deg, #21d07a, #05a85b)',
                    'pending' => 'linear-gradient(135deg, #ffc107, #ff9800)',
                    'gagal' => 'linear-gradient(135deg, #dc3545, #c82333)'
                ];
                $bgColor = $statusColors[$trans['status_pembayaran']] ?? '#6c757d';
                ?>
                <div style="display: inline-block; padding: 15px 40px; background: <?php echo $bgColor; ?>; color: white; border-radius: 50px; font-size: 18px; font-weight: 700; box-shadow: 0 4px 12px rgba(0,0,0,0.2);">
                    <?php echo strtoupper($trans['status_pembayaran']); ?>
                </div>
            </div>

            <!-- Kode Booking -->
            <div style="text-align: center; margin-bottom: 30px; padding: 20px; background: #f8f9fa; border-radius: 10px;">
                <p style="margin: 0 0 10px 0; color: #666; font-size: 14px;">Kode Booking</p>
                <h2 style="margin: 0; color: #032541; font-size: 32px; letter-spacing: 2px; font-family: monospace;">
                    <?php echo htmlspecialchars($trans['kode_booking']); ?>
                </h2>
            </div>

            <div class="info-grid">
                <!-- Pelanggan -->
                <div class="info-item">
                    <strong>👤 Nama Pelanggan:</strong>
                    <p><?php echo htmlspecialchars($trans['nama_user']); ?></p>
                </div>

                <div class="info-item">
                    <strong>📧 Email:</strong>
                    <p><?php echo htmlspecialchars($trans['email']); ?></p>
                </div>

                <?php if(!empty($trans['no_telpon'])): ?>
                <div class="info-item">
                    <strong>📱 No. Telepon:</strong>
                    <p><?php echo htmlspecialchars($trans['no_telpon']); ?></p>
                </div>
                <?php endif; ?>

                <!-- Transaksi Info -->
                <div class="info-item">
                    <strong>📅 Tanggal Transaksi:</strong>
                    <p><?php echo date('d F Y, H:i', strtotime($trans['tanggal_transaksi'])); ?></p>
                </div>

                <div class="info-item">
                    <strong>🎫 Jumlah Tiket:</strong>
                    <p><?php echo $trans['jumlah_tiket']; ?> tiket</p>
                </div>

                <div class="info-item">
                    <strong>💳 Metode Pembayaran:</strong>
                    <p><?php 
                    $metodePembayaran = [
                        'transfer' => 'Transfer Bank',
                        'e-wallet' => 'E-Wallet',
                        'kartu_kredit' => 'Kartu Kredit',
                        'tunai' => 'Tunai'
                    ];
                    echo $metodePembayaran[$trans['metode_pembayaran']] ?? $trans['metode_pembayaran'];
                    ?></p>
                </div>

                <div class="info-item" style="grid-column: span 2;">
                    <strong>💰 Total Pembayaran:</strong>
                    <p style="color: #01b4e4; font-size: 24px; font-weight: 700;">
                        Rp <?php echo number_format($trans['total_harga'], 0, ',', '.'); ?>
                    </p>
                </div>
            </div>

            <?php if($firstTicket): ?>
            <!-- Detail Film & Bioskop -->
            <div style="margin-top: 30px; padding: 20px; background: #f8f9fa; border-radius: 10px;">
                <h3 style="margin: 0 0 15px 0; color: #032541;">🎬 Informasi Film & Bioskop</h3>
                
                <div style="display: grid; gap: 10px;">
                    <p style="margin: 0;"><strong>Film:</strong> <?php echo htmlspecialchars($firstTicket['judul_film']); ?></p>
                    <p style="margin: 0;"><strong>Durasi:</strong> <?php echo $firstTicket['durasi_menit']; ?> menit</p>
                    <p style="margin: 0;"><strong>Bioskop:</strong> <?php echo htmlspecialchars($firstTicket['nama_bioskop']); ?></p>
                    <p style="margin: 0;"><strong>Kota:</strong> <?php echo htmlspecialchars($firstTicket['kota']); ?></p>
                    <p style="margin: 0;"><strong>Alamat:</strong> <?php echo htmlspecialchars($firstTicket['alamat_bioskop']); ?></p>
                    <p style="margin: 0;"><strong>Tanggal Tayang:</strong> <?php echo date('l, d F Y', strtotime($firstTicket['tanggal_tayang'])); ?></p>
                    <p style="margin: 0;"><strong>Waktu:</strong> <?php echo date('H:i', strtotime($firstTicket['jam_mulai'])); ?> - <?php echo date('H:i', strtotime($firstTicket['jam_selesai'])); ?> WIB</p>
                </div>
            </div>
            <?php endif; ?>

            <!-- Detail Tiket -->
            <div style="margin-top: 30px;">
                <h3 style="margin: 0 0 15px 0; color: #032541;">🎫 Detail Tiket</h3>
                <div style="display: grid; gap: 10px;">
                    <?php foreach($tickets as $index => $ticket): ?>
                    <div style="padding: 15px; background: white; border-radius: 8px; border: 2px solid #01b4e4; display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <span style="font-weight: 700; color: #032541;">Tiket #<?php echo ($index + 1); ?></span>
                            <span style="margin-left: 15px; padding: 5px 15px; background: #01b4e4; color: white; border-radius: 20px; font-weight: 600; font-size: 16px;">
                                Kursi <?php echo htmlspecialchars($ticket['nomor_kursi']); ?>
                            </span>
                        </div>
                        <div style="text-align: right;">
                            <div style="font-size: 12px; color: #666;"><?php echo ucfirst($ticket['jenis_tiket']); ?></div>
                            <div style="font-weight: 600; color: #01b4e4;">Rp <?php echo number_format($ticket['harga_tiket'], 0, ',', '.'); ?></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Update Status -->
            <?php if($trans['status_pembayaran'] == 'pending'): ?>
            <div style="margin-top: 30px; padding: 20px; background: #fff3cd; border: 2px solid #ffc107; border-radius: 10px;">
                <h3 style="margin: 0 0 15px 0; color: #856404;">⚠️ Status Pending</h3>
                <p style="margin: 0 0 15px 0; color: #856404;">Transaksi ini masih menunggu konfirmasi pembayaran.</p>
                
                <form method="POST" action="index.php?module=admin&action=updateStatus" style="display: flex; gap: 10px;">
                    <input type="hidden" name="id_transaksi" value="<?php echo $trans['id_transaksi']; ?>">
                    
                    <button type="submit" name="status" value="berhasil" class="btn btn-success" style="flex: 1;">
                        ✓ Konfirmasi Berhasil
                    </button>
                    
                    <button type="submit" name="status" value="gagal" class="btn btn-danger" style="flex: 1;"
                            onclick="return confirm('Yakin ubah status menjadi GAGAL?')">
                        ✗ Tandai Gagal
                    </button>
                </form>
            </div>
            <?php endif; ?>

            <div class="detail-actions" style="margin-top: 30px;">
                <button onclick="window.print()" class="btn btn-info">🖨️ Cetak Detail</button>
                <a href="index.php?module=admin&action=laporanTransaksi" class="btn btn-secondary">📋 Kembali ke Laporan</a>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    .navbar, .footer, .btn, form {
        display: none !important;
    }
    .header-section a {
        display: none !important;
    }
}
</style>

<?php require_once 'views/layouts/footer.php'; ?>