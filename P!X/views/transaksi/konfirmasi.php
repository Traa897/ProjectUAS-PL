<?php require_once 'views/layouts/header.php'; ?>

<div class="container" style="max-width: 600px;">
    <?php 
    $data = $detailTransaksi;
    $trans = $data['transaksi'];
    $tickets = $data['tickets'];
    $firstTicket = $tickets[0] ?? null;
    
    // Cek apakah pre-sale
    $today = date('Y-m-d');
    $tanggalTayang = $firstTicket['tanggal_tayang'] ?? '';
    $selisihHari = floor((strtotime($tanggalTayang) - strtotime($today)) / 86400);
    $isPresale = ($selisihHari > 1);
    ?>

    <!-- Struk Pembayaran - Simple Style -->
    <div id="struk" style="background: white; padding: 20px 30px; margin: 30px auto; max-width: 400px; font-family: 'Courier New', monospace; border: 2px dashed #666;">
        
        <!-- Header -->
        <div style="text-align: center; border-bottom: 1px dashed #666; padding-bottom: 15px; margin-bottom: 15px;">
            <h1 style="margin: 0; font-size: 28px; letter-spacing: 3px;">P!X</h1>
            <p style="margin: 5px 0 0 0; font-size: 11px;">SISTEM BIOSKOP DIGITAL</p>
            <p style="margin: 3px 0 0 0; font-size: 10px;">www.pix-bioskop.com</p>
        </div>

        <!-- Status -->
        <div style="text-align: center; margin-bottom: 15px;">
            <div style="background: #000; color: #fff; padding: 8px; font-size: 13px; font-weight: bold;">
                ✓ PEMBAYARAN BERHASIL
            </div>
            <?php if($isPresale): ?>
            <div style="background: #23acdaff; color: #fff; padding: 6px; font-size: 11px; font-weight: bold; margin-top: 5px;">
                ⚡ PRE-SALE TICKET
            </div>
            <?php endif; ?>
        </div>

        <!-- Kode Booking -->
        <div style="text-align: center; margin-bottom: 15px; padding: 10px 0; border-top: 1px dashed #666; border-bottom: 1px dashed #666;">
            <p style="margin: 0; font-size: 9px;">KODE BOOKING</p>
            <h2 style="margin: 5px 0; font-size: 18px; letter-spacing: 2px;">
                <?php echo htmlspecialchars($trans['kode_booking']); ?>
            </h2>
        </div>

        <!-- Detail Pelanggan -->
        <div style="margin-bottom: 15px; font-size: 11px;">
            <p style="margin: 0; font-weight: bold; margin-bottom: 5px;">PELANGGAN:</p>
            <p style="margin: 2px 0;"><?php echo htmlspecialchars($trans['nama_user']); ?></p>
            <p style="margin: 2px 0;"><?php echo htmlspecialchars($trans['email']); ?></p>
            <?php if(!empty($trans['no_telpon'])): ?>
            <p style="margin: 2px 0;"><?php echo htmlspecialchars($trans['no_telpon']); ?></p>
            <?php endif; ?>
        </div>

        <div style="border-top: 1px dashed #666; margin: 15px 0;"></div>

        <?php if($firstTicket): ?>
        <!-- Detail Film -->
        <div style="margin-bottom: 15px; font-size: 11px;">
            <p style="margin: 0; font-weight: bold;">FILM:</p>
            <p style="margin: 2px 0;"><?php echo htmlspecialchars($firstTicket['judul_film']); ?></p>
            <p style="margin: 2px 0;">Durasi: <?php echo $firstTicket['durasi_menit']; ?> menit</p>
        </div>

        <!-- Detail Bioskop -->
        <div style="margin-bottom: 15px; font-size: 11px;">
            <p style="margin: 0; font-weight: bold;">BIOSKOP:</p>
            <p style="margin: 2px 0;"><?php echo htmlspecialchars($firstTicket['nama_bioskop']); ?></p>
            <p style="margin: 2px 0;"><?php echo htmlspecialchars($firstTicket['kota']); ?></p>
            <p style="margin: 2px 0; font-size: 10px;"><?php echo htmlspecialchars($firstTicket['alamat_bioskop']); ?></p>
        </div>

        <!-- Jadwal -->
        <div style="margin-bottom: 15px; font-size: 11px;">
            <p style="margin: 0; font-weight: bold;">JADWAL:</p>
            <p style="margin: 2px 0;"><?php echo date('l, d F Y', strtotime($firstTicket['tanggal_tayang'])); ?></p>
            <p style="margin: 2px 0;">
                Pukul: <?php echo date('H:i', strtotime($firstTicket['jam_mulai'])); ?> - 
                <?php echo date('H:i', strtotime($firstTicket['jam_selesai'])); ?> WIB
            </p>
            
            <?php if($isPresale): ?>
            <p style="margin: 5px 0 0 0; padding: 5px; background: #fff3cd; font-size: 10px; border: 1px solid #ffc107;">
                <strong>PRE-SALE:</strong> Tiket berlaku untuk penayangan <?php echo $selisihHari; ?> hari lagi
            </p>
            <?php endif; ?>
        </div>

        <div style="border-top: 1px dashed #666; margin: 15px 0;"></div>
        <?php endif; ?>

        <!-- Detail Tiket -->
        <div style="margin-bottom: 15px; font-size: 11px;">
            <p style="margin: 0; font-weight: bold; margin-bottom: 5px;">TIKET:</p>
            <?php foreach($tickets as $index => $ticket): ?>
            <div style="display: flex; justify-content: space-between; margin: 3px 0; padding: 5px; background: #f5f5f5;">
                <span>Tiket #<?php echo ($index + 1); ?> - Kursi <?php echo htmlspecialchars($ticket['nomor_kursi']); ?></span>
                <span>Rp <?php echo number_format($ticket['harga_tiket'], 0, ',', '.'); ?></span>
            </div>
            <?php endforeach; ?>
        </div>

        <div style="border-top: 1px dashed #666; margin: 15px 0;"></div>

        <!-- Ringkasan -->
        <div style="font-size: 11px; margin-bottom: 15px;">
            <div style="display: flex; justify-content: space-between; margin: 3px 0;">
                <span>Jumlah Tiket:</span>
                <span><?php echo $trans['jumlah_tiket']; ?> tiket</span>
            </div>
            <div style="display: flex; justify-content: space-between; margin: 3px 0;">
                <span>Metode Bayar:</span>
                <span><?php 
                $metode = [
                    'transfer' => 'Transfer',
                    'e-wallet' => 'E-Wallet',
                    'kartu_kredit' => 'Kartu Kredit',
                    'tunai' => 'Tunai'
                ];
                echo $metode[$trans['metode_pembayaran']] ?? $trans['metode_pembayaran'];
                ?></span>
            </div>
            <div style="display: flex; justify-content: space-between; margin: 3px 0;">
                <span>Tanggal:</span>
                <span><?php echo date('d/m/Y H:i', strtotime($trans['tanggal_transaksi'])); ?></span>
            </div>
        </div>

        <div style="border-top: 2px solid #000; margin: 15px 0;"></div>

        <!-- Total -->
        <div style="display: flex; justify-content: space-between; font-size: 14px; font-weight: bold; margin-bottom: 15px;">
            <span>TOTAL BAYAR:</span>
            <span>Rp <?php echo number_format($trans['total_harga'], 0, ',', '.'); ?></span>
        </div>

        <div style="border-top: 1px dashed #666; margin: 15px 0;"></div>

        <!-- Footer -->
        <div style="text-align: center; font-size: 9px;">
            <p style="margin: 3px 0;">TERIMA KASIH</p>
            <p style="margin: 3px 0;">Tunjukkan struk ini di kasir</p>
            <p style="margin: 3px 0;">Simpan sebagai bukti pembayaran</p>
            <p style="margin: 10px 0 0 0; font-size: 8px;">
                <?php echo date('d/m/Y H:i:s'); ?>
            </p>
        </div>

    </div>

    <!-- Action Buttons -->
    <div style="display: flex; gap: 10px; margin: 30px auto; max-width: 400px;">
        <button onclick="window.print()" class="btn btn-primary" style="flex: 1;">
             Cetak
        </button>
        <a href="index.php?module=film" class="btn btn-info" style="flex: 1; text-align: center;">
             Home
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
        left: 50%;
        top: 0;
        transform: translateX(-50%);
        width: 400px;
        border: none;
    }
    .navbar, .footer, .btn, .container > div:last-child {
        display: none !important;
    }
}
</style>

<?php require_once 'views/layouts/footer.php'; ?>