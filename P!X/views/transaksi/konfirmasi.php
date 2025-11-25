<?php require_once 'views/layouts/header.php'; ?>

<div class="container" style="max-width: 800px;">
    <div class="header-section">
        <h1>✅ Booking Berhasil!</h1>
    </div>

    <?php 
    $data = $detailTransaksi;
    $trans = $data['transaksi'];
    $tickets = $data['tickets'];
    $firstTicket = $tickets[0] ?? null;
    ?>

    <!-- Struk Pembayaran -->
    <div id="struk" style="background: white; padding: 40px; border-radius: 10px; box-shadow: 0 4px 16px rgba(0,0,0,0.1); margin-bottom: 30px;">
        <!-- Header Struk -->
        <div style="text-align: center; border-bottom: 3px dashed #032541; padding-bottom: 25px; margin-bottom: 25px;">
            <h1 style="margin: 0; color: #01b4e4; font-size: 36px; letter-spacing: 3px;">P!X</h1>
            <p style="margin: 5px 0 0 0; color: #666; font-size: 14px;">Sistem Bioskop Digital</p>
        </div>

        <!-- Status Badge -->
        <div style="text-align: center; margin-bottom: 30px;">
            <div style="display: inline-block; padding: 15px 40px; background: linear-gradient(135deg, #21d07a, #05a85b); color: white; border-radius: 50px; font-size: 18px; font-weight: 700; box-shadow: 0 4px 12px rgba(33,208,122,0.3);">
                ✓ PEMBAYARAN BERHASIL
            </div>
        </div>

        <!-- Kode Booking -->
        <div style="text-align: center; margin-bottom: 30px; padding: 20px; background: #f8f9fa; border-radius: 10px;">
            <p style="margin: 0 0 10px 0; color: #666; font-size: 14px;">Kode Booking</p>
            <h2 style="margin: 0; color: #032541; font-size: 32px; letter-spacing: 2px; font-family: monospace;">
                <?php echo htmlspecialchars($trans['kode_booking']); ?>
            </h2>
            <p style="margin: 10px 0 0 0; color: #01b4e4; font-size: 12px;">Simpan kode ini untuk verifikasi di bioskop</p>
        </div>

        <!-- Detail Pelanggan -->
        <div style="margin-bottom: 25px; padding: 20px; background: #f8f9fa; border-radius: 10px;">
            <h3 style="margin: 0 0 15px 0; color: #032541; font-size: 18px;">👤 Detail Pelanggan</h3>
            <table style="width: 100%; font-size: 14px;">
                <tr>
                    <td style="padding: 5px 0; color: #666;">Nama</td>
                    <td style="padding: 5px 0; text-align: right; font-weight: 600;">
                        <?php echo htmlspecialchars($trans['nama_user']); ?>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 5px 0; color: #666;">Email</td>
                    <td style="padding: 5px 0; text-align: right; font-weight: 600;">
                        <?php echo htmlspecialchars($trans['email']); ?>
                    </td>
                </tr>
                <?php if(!empty($trans['no_telpon'])): ?>
                <tr>
                    <td style="padding: 5px 0; color: #666;">No. Telepon</td>
                    <td style="padding: 5px 0; text-align: right; font-weight: 600;">
                        <?php echo htmlspecialchars($trans['no_telpon']); ?>
                    </td>
                </tr>
                <?php endif; ?>
            </table>
        </div>

        <?php if($firstTicket): ?>
        <!-- Detail Film -->
        <div style="margin-bottom: 25px; padding: 20px; background: #f8f9fa; border-radius: 10px;">
            <h3 style="margin: 0 0 15px 0; color: #032541; font-size: 18px;">🎬 Detail Film</h3>
            <table style="width: 100%; font-size: 14px;">
                <tr>
                    <td style="padding: 5px 0; color: #666;">Judul Film</td>
                    <td style="padding: 5px 0; text-align: right; font-weight: 600;">
                        <?php echo htmlspecialchars($firstTicket['judul_film']); ?>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 5px 0; color: #666;">Durasi</td>
                    <td style="padding: 5px 0; text-align: right; font-weight: 600;">
                        <?php echo $firstTicket['durasi_menit']; ?> menit
                    </td>
                </tr>
            </table>
        </div>

        <!-- Detail Bioskop -->
        <div style="margin-bottom: 25px; padding: 20px; background: #f8f9fa; border-radius: 10px;">
            <h3 style="margin: 0 0 15px 0; color: #032541; font-size: 18px;">🏢 Detail Bioskop</h3>
            <table style="width: 100%; font-size: 14px;">
                <tr>
                    <td style="padding: 5px 0; color: #666;">Bioskop</td>
                    <td style="padding: 5px 0; text-align: right; font-weight: 600;">
                        <?php echo htmlspecialchars($firstTicket['nama_bioskop']); ?>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 5px 0; color: #666;">Lokasi</td>
                    <td style="padding: 5px 0; text-align: right; font-weight: 600;">
                        <?php echo htmlspecialchars($firstTicket['kota']); ?>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 5px 0; color: #666;">Alamat</td>
                    <td style="padding: 5px 0; text-align: right; font-weight: 600;">
                        <?php echo htmlspecialchars($firstTicket['alamat_bioskop']); ?>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Jadwal Tayang -->
        <div style="margin-bottom: 25px; padding: 20px; background: #f8f9fa; border-radius: 10px;">
            <h3 style="margin: 0 0 15px 0; color: #032541; font-size: 18px;">📅 Jadwal Tayang</h3>
            <table style="width: 100%; font-size: 14px;">
                <tr>
                    <td style="padding: 5px 0; color: #666;">Tanggal</td>
                    <td style="padding: 5px 0; text-align: right; font-weight: 600;">
                        <?php echo date('l, d F Y', strtotime($firstTicket['tanggal_tayang'])); ?>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 5px 0; color: #666;">Waktu</td>
                    <td style="padding: 5px 0; text-align: right; font-weight: 600;">
                        <?php echo date('H:i', strtotime($firstTicket['jam_mulai'])); ?> - 
                        <?php echo date('H:i', strtotime($firstTicket['jam_selesai'])); ?> WIB
                    </td>
                </tr>
            </table>
        </div>
        <?php endif; ?>

        <!-- Detail Tiket -->
        <div style="margin-bottom: 25px; padding: 20px; background: #f8f9fa; border-radius: 10px;">
            <h3 style="margin: 0 0 15px 0; color: #032541; font-size: 18px;">🎫 Detail Tiket</h3>
            <div style="display: grid; gap: 10px;">
                <?php foreach($tickets as $index => $ticket): ?>
                <div style="padding: 15px; background: white; border-radius: 8px; border: 2px solid #01b4e4; display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <span style="font-weight: 700; color: #032541;">Tiket #<?php echo ($index + 1); ?></span>
                        <span style="margin-left: 15px; padding: 5px 15px; background: #01b4e4; color: white; border-radius: 20px; font-weight: 600; font-size: 16px;">
                            Kursi <?php echo htmlspecialchars($ticket['nomor_kursi']); ?>
                        </span>
                    </div>
                    <span style="font-weight: 600; color: #666;">
                        <?php echo ucfirst($ticket['jenis_tiket']); ?>
                    </span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Ringkasan Pembayaran -->
        <div style="border-top: 3px dashed #032541; padding-top: 25px; margin-top: 25px;">
            <h3 style="margin: 0 0 15px 0; color: #032541; font-size: 18px;">💰 Ringkasan Pembayaran</h3>
            <table style="width: 100%; font-size: 14px; margin-bottom: 15px;">
                <tr>
                    <td style="padding: 8px 0; color: #666;">Jumlah Tiket</td>
                    <td style="padding: 8px 0; text-align: right; font-weight: 600;">
                        <?php echo $trans['jumlah_tiket']; ?> tiket × Rp <?php echo number_format($trans['total_harga'] / $trans['jumlah_tiket'], 0, ',', '.'); ?>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; color: #666;">Metode Pembayaran</td>
                    <td style="padding: 8px 0; text-align: right; font-weight: 600;">
                        <?php 
                        $metodePembayaran = [
                            'transfer' => 'Transfer Bank',
                            'e-wallet' => 'E-Wallet',
                            'kartu_kredit' => 'Kartu Kredit',
                            'tunai' => 'Tunai'
                        ];
                        echo $metodePembayaran[$trans['metode_pembayaran']] ?? $trans['metode_pembayaran'];
                        ?>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; color: #666;">Tanggal Transaksi</td>
                    <td style="padding: 8px 0; text-align: right; font-weight: 600;">
                        <?php echo date('d/m/Y H:i', strtotime($trans['tanggal_transaksi'])); ?>
                    </td>
                </tr>
            </table>
            
            <div style="padding: 20px; background: #032541; color: white; border-radius: 10px; display: flex; justify-content: space-between; align-items: center;">
                <span style="font-size: 18px; font-weight: 600;">Total Bayar</span>
                <span style="font-size: 28px; font-weight: 700;">
                    Rp <?php echo number_format($trans['total_harga'], 0, ',', '.'); ?>
                </span>
            </div>
        </div>

        <!-- Footer Struk -->
        <div style="text-align: center; margin-top: 30px; padding-top: 20px; border-top: 2px dashed #e0e0e0;">
            <p style="margin: 0; color: #666; font-size: 12px;">Terima kasih telah menggunakan P!X</p>
            <p style="margin: 5px 0 0 0; color: #666; font-size: 12px;">Tunjukkan struk ini dan kode booking untuk verifikasi di bioskop</p>
        </div>
    </div>

    <!-- Action Buttons -->
    <div style="display: flex; gap: 15px; margin-bottom: 50px;">
        <button onclick="window.print()" class="btn btn-primary" style="flex: 1;">
            🖨️ Cetak Struk
        </button>
        <a href="index.php?module=user&action=dashboard" class="btn btn-secondary" style="flex: 1; text-align: center;">
            📋 Lihat Riwayat
        </a>
        <a href="index.php?module=film" class="btn btn-info" style="flex: 1; text-align: center;">
            🎬 Kembali ke Beranda
        </a>
    </div>
</div>

<style>
@media print {
    body * {
        visibility: hidden;
    }
    #struk, #struk * {
        visibility: visible;
    }
    #struk {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
    }
    .navbar, .footer, .btn {
        display: none !important;
    }
}
</style>

<?php require_once 'views/layouts/footer.php'; ?>