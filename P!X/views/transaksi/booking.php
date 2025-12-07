<?php require_once 'views/layouts/header.php'; ?>

<div class="container">
    <div class="header-section">
        <h1>Booking Tiket</h1>
        <a href="index.php?module=transaksi&action=pilihJadwal&id_film=<?php echo $this->jadwal->id_film; ?>" class="btn btn-secondary">⬅️ Kembali</a>
    </div>

    <?php
    // PERBAIKAN: Hitung selisih hari dengan benar
    $today = date('Y-m-d');
    $tanggalTayang = $this->jadwal->tanggal_tayang;
    $selisihHari = floor((strtotime($tanggalTayang) - strtotime($today)) / 86400);
    
    // Tentukan status
    $isPresale = ($selisihHari > 1); // Lebih dari 1 hari = Pre-Sale
    $isToday = ($selisihHari == 0);
    $isTomorrow = ($selisihHari == 1);
    ?>

    <!-- Pre-Sale Banner -->
    <?php if($isPresale): ?>
    <div style="background: #FFE8AD; padding: 25px; border-radius: 10px; margin-bottom: 25px; color: #333; box-shadow: 0 4px 16px rgba(255, 232, 173, 0.4);">
        <div style="display: flex; align-items: center; gap: 20px;">
            <div style="font-size: 48px;">⚡</div>
            <div style="flex: 1;">
                <h3 style="margin: 0 0 8px 0; font-size: 22px; color: #333;">Pre-Sale Booking</h3>
                <p style="margin: 0; font-size: 15px; color: #555;">
                    Anda sedang melakukan pre-sale booking untuk penayangan <strong><?php echo date('l, d F Y', strtotime($tanggalTayang)); ?></strong>. 
                    Tiket dapat digunakan pada tanggal tersebut.
                </p>
            </div>
            <div style="background: rgba(0,0,0,0.1); padding: 15px 25px; border-radius: 20px; text-align: center; min-width: 100px; border: 2px solid rgba(0,0,0,0.15);">
                <div style="font-size: 32px; font-weight: 700; color: #333;">
                    <?php echo $selisihHari; ?>
                </div>
                <div style="font-size: 12px; color: #555;">HARI LAGI</div>
            </div>
        </div>
    </div>
    <?php elseif($isToday): ?>
    <div style="background: #0281AA; padding: 25px; border-radius: 10px; margin-bottom: 25px; color: white; box-shadow: 0 4px 16px rgba(2, 129, 170, 0.4);">
        <div style="display: flex; align-items: center; gap: 20px;">
            <div style="font-size: 48px;">🔥</div>
            <div style="flex: 1;">
                <h3 style="margin: 0 0 8px 0; font-size: 22px;">Tayang Hari Ini!</h3>
                <p style="margin: 0; opacity: 0.95; font-size: 15px;">
                    Film ini tayang hari ini pada jam <?php echo date('H:i', strtotime($this->jadwal->jam_mulai)); ?> WIB. 
                    Segera booking sebelum tiket habis!
                </p>
            </div>
        </div>
    </div>
    <?php elseif($isTomorrow): ?>
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 25px; border-radius: 10px; margin-bottom: 25px; color: white; box-shadow: 0 4px 16px rgba(102, 126, 234, 0.4);">
        <div style="display: flex; align-items: center; gap: 20px;">
            <div style="font-size: 48px;">⏭️</div>
            <div style="flex: 1;">
                <h3 style="margin: 0 0 8px 0; font-size: 22px;">Tayang Besok!</h3>
                <p style="margin: 0; opacity: 0.95; font-size: 15px;">
                    Film ini akan tayang besok pada jam <?php echo date('H:i', strtotime($this->jadwal->jam_mulai)); ?> WIB.
                </p>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <div class="detail-container" style="grid-template-columns: 2fr 1fr;">
        <!-- Film Info -->
        <div>
            <div style="background: #f8f9fa; padding: 25px; border-radius: 10px; margin-bottom: 25px;">
                <h3 style="margin: 0 0 15px 0; color: #032541;">🎬 Informasi Film</h3>
                <p style="margin: 5px 0;"><strong>Film:</strong> <?php echo htmlspecialchars($this->jadwal->judul_film); ?></p>
                <p style="margin: 5px 0;"><strong>Bioskop:</strong> <?php echo htmlspecialchars($this->jadwal->nama_bioskop); ?></p>
                <p style="margin: 5px 0;"><strong>Lokasi:</strong> <?php echo htmlspecialchars($this->jadwal->kota); ?></p>
                <p style="margin: 5px 0;"><strong>Tanggal:</strong> 
                    <?php 
                    $hari = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                    echo $hari[date('w', strtotime($this->jadwal->tanggal_tayang))]; 
                    ?>, <?php echo date('d F Y', strtotime($this->jadwal->tanggal_tayang)); ?>
                </p>
                <p style="margin: 5px 0;"><strong>Jam:</strong> <?php echo date('H:i', strtotime($this->jadwal->jam_mulai)); ?> - <?php echo date('H:i', strtotime($this->jadwal->jam_selesai)); ?> WIB</p>
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
                               style="width: 100%; padding: 12px; border: 2px solid #e0e0e0; border-radius: 5px; font-size: 16px;">
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
                        <div style="display: flex; flex-wrap: wrap; gap: 5px; margin-top: 10px; max-height: 150px; overflow-y: auto; padding: 10px; background: #f8f9fa; border-radius: 5px;">
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
                        <select name="metode_pembayaran" required style="width: 100%; padding: 12px; border: 2px solid #e0e0e0; border-radius: 5px; font-size: 16px;">
                            <option value="transfer">🏦 Transfer Bank</option>
                            <option value="e-wallet">📱 E-Wallet (GoPay, OVO, Dana)</option>
                            <option value="kartu_kredit">💳 Kartu Kredit</option>
                            <option value="tunai">💵 Tunai di Kasir</option>
                        </select>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary" style="width: 100%; padding: 15px; font-size: 18px;">
                            Konfirmasi Booking
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Order Summary -->
        <div>
            <div style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); position: sticky; top: 20px;">
                <h3 style="margin: 0 0 20px 0; color: #032541;">📝 Ringkasan Pesanan</h3>
                
                <?php if($isPresale): ?>
                <div style="background: #FFE8AD; color: #333; padding: 12px; border-radius: 8px; margin-bottom: 15px; text-align: center; font-weight: 600; font-size: 13px;">
                    ⚡ PRE-SALE BOOKING
                </div>
                <?php elseif($isToday): ?>
                <div style="background: #0281AA; color: white; padding: 12px; border-radius: 8px; margin-bottom: 15px; text-align: center; font-weight: 600; font-size: 13px;">
                    🔥 TAYANG HARI INI
                </div>
                <?php endif; ?>
                
                <div style="border-bottom: 2px dashed #e0e0e0; padding-bottom: 15px; margin-bottom: 15px;">
                    <p style="margin: 8px 0; display: flex; justify-content: space-between;">
                        <span>Harga Tiket:</span>
                        <span id="harga_satuan">Rp <?php echo number_format($this->jadwal->harga_tiket, 0, ',', '.'); ?></span>
                    </p>
                    <p style="margin: 8px 0; display: flex; justify-content: space-between;">
                        <span>Jumlah Tiket:</span>
                        <span id="qty">1</span>
                    </p>
                    <?php if($isPresale): ?>
                    <p style="margin: 8px 0; display: flex; justify-content: space-between; font-size: 13px; color: #f5576c;">
                        <span>Status:</span>
                        <span style="font-weight: 600;">Pre-Sale</span>
                    </p>
                    <?php endif; ?>
                </div>
                
                <p style="margin: 0; display: flex; justify-content: space-between; font-size: 20px; font-weight: 700; color: #01b4e4;">
                    <span>Total:</span>
                    <span id="total_harga">Rp <?php echo number_format($this->jadwal->harga_tiket, 0, ',', '.'); ?></span>
                </p>

                <?php if($isPresale): ?>
                <div style="margin-top: 20px; padding: 15px; background: #fff3cd; border-radius: 8px; font-size: 13px; color: #856404;">
                    <strong>📅 Tanggal Tayang:</strong><br>
                    <?php echo date('d F Y', strtotime($tanggalTayang)); ?><br>
                    <strong style="margin-top: 5px; display: inline-block;">⏰ <?php echo $selisihHari; ?> hari lagi</strong>
                </div>
                <?php endif; ?>
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