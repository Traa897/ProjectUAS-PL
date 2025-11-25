<?php require_once 'views/layouts/header.php'; ?>

<div class="container">
    <div class="header-section">
        <h1>🎫 Booking Tiket</h1>
        <a href="index.php?module=transaksi&action=pilihJadwal&id_film=<?php echo $this->jadwal->id_film; ?>" class="btn btn-secondary">⬅️ Kembali</a>
    </div>

    <div class="detail-container" style="grid-template-columns: 2fr 1fr;">
        <!-- Film Info -->
        <div>
            <div style="background: #f8f9fa; padding: 25px; border-radius: 10px; margin-bottom: 25px;">
                <h3 style="margin: 0 0 15px 0; color: #032541;">🎬 Informasi Film</h3>
                <p style="margin: 5px 0;"><strong>Film:</strong> <?php echo htmlspecialchars($this->jadwal->judul_film); ?></p>
                <p style="margin: 5px 0;"><strong>Bioskop:</strong> <?php echo htmlspecialchars($this->jadwal->nama_bioskop); ?></p>
                <p style="margin: 5px 0;"><strong>Tanggal:</strong> <?php echo date('d F Y', strtotime($this->jadwal->tanggal_tayang)); ?></p>
                <p style="margin: 5px 0;"><strong>Jam:</strong> <?php echo date('H:i', strtotime($this->jadwal->jam_mulai)); ?> - <?php echo date('H:i', strtotime($this->jadwal->jam_selesai)); ?></p>
                <p style="margin: 5px 0;"><strong>Harga per Tiket:</strong> <span style="color: #01b4e4; font-weight: 700;">Rp <?php echo number_format($this->jadwal->harga_tiket, 0, ',', '.'); ?></span></p>
            </div>

            <!-- Seat Selection -->
            <div style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                <h3 style="margin: 0 0 20px 0; color: #032541;">💺 Pilih Jumlah Tiket</h3>
                
                <form method="POST" action="index.php?module=transaksi&action=prosesBooking" id="bookingForm">
                    <input type="hidden" name="id_jadwal" value="<?php echo $this->jadwal->id_tayang; ?>">
                    
                    <div class="form-group">
                        <label>Jumlah Tiket *</label>
                        <input type="number" id="jumlah_tiket" name="jumlah_tiket" min="1" max="10" value="1" required 
                               style="width: 100%; padding: 12px; border: 2px solid #e0e0e0; border-radius: 5px;">
                        <small style="color: #666;">Kursi akan dipilih secara otomatis (Random)</small>
                    </div>

                    <div style="background: #fff3cd; border: 2px solid #ffc107; border-radius: 8px; padding: 15px; margin-bottom: 20px;">
                        <strong style="color: #856404;">ℹ️ Informasi Kursi:</strong>
                        <ul style="margin: 10px 0 0 0; padding-left: 20px; color: #856404;">
                            <li>Kursi akan dipilih secara RANDOM oleh sistem</li>
                            <li>Sistem akan memilih kursi terbaik yang tersedia</li>
                            <li>Kursi yang sudah terpesan: <?php echo count($kursiTerpesan); ?> kursi</li>
                            <li>Kursi tersedia: <?php echo (100 - count($kursiTerpesan)); ?> kursi</li>
                        </ul>
                    </div>

                    <!-- Tampilkan Kursi Terpesan -->
                    <?php if(!empty($kursiTerpesan)): ?>
                    <div style="margin-bottom: 20px;">
                        <strong style="color: #dc3545;">🚫 Kursi Sudah Terpesan:</strong>
                        <div style="display: flex; flex-wrap: wrap; gap: 5px; margin-top: 10px;">
                            <?php foreach($kursiTerpesan as $kursi): ?>
                                <span style="padding: 5px 10px; background: #dc3545; color: white; border-radius: 5px; font-size: 12px;">
                                    <?php echo htmlspecialchars($kursi); ?>
                                </span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <div class="form-group">
                        <label>Metode Pembayaran *</label>
                        <select name="metode_pembayaran" required style="width: 100%; padding: 12px; border: 2px solid #e0e0e0; border-radius: 5px;">
                            <option value="transfer">Transfer Bank</option>
                            <option value="e-wallet">E-Wallet (GoPay, OVO, Dana)</option>
                            <option value="kartu_kredit">Kartu Kredit</option>
                            <option value="tunai">Tunai di Kasir</option>
                        </select>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary" style="width: 100%;">💳 Proses Booking</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Order Summary -->
        <div>
            <div style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); position: sticky; top: 20px;">
                <h3 style="margin: 0 0 20px 0; color: #032541;">📝 Ringkasan Pesanan</h3>
                
                <div style="border-bottom: 2px dashed #e0e0e0; padding-bottom: 15px; margin-bottom: 15px;">
                    <p style="margin: 8px 0; display: flex; justify-content: space-between;">
                        <span>Harga Tiket:</span>
                        <span id="harga_satuan">Rp <?php echo number_format($this->jadwal->harga_tiket, 0, ',', '.'); ?></span>
                    </p>
                    <p style="margin: 8px 0; display: flex; justify-content: space-between;">
                        <span>Jumlah Tiket:</span>
                        <span id="qty">1</span>
                    </p>
                </div>
                
                <p style="margin: 0; display: flex; justify-content: space-between; font-size: 20px; font-weight: 700; color: #01b4e4;">
                    <span>Total:</span>
                    <span id="total_harga">Rp <?php echo number_format($this->jadwal->harga_tiket, 0, ',', '.'); ?></span>
                </p>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const jumlahTiketInput = document.getElementById('jumlah_tiket');
    const qtyDisplay = document.getElementById('qty');
    const totalDisplay = document.getElementById('total_harga');
    const hargaSatuan = <?php echo $this->jadwal->harga_tiket; ?>;
    
    jumlahTiketInput.addEventListener('input', function() {
        const qty = parseInt(this.value) || 0;
        const total = qty * hargaSatuan;
        
        qtyDisplay.textContent = qty;
        totalDisplay.textContent = 'Rp ' + total.toLocaleString('id-ID');
    });
});
</script>

<?php require_once 'views/layouts/footer.php'; ?>