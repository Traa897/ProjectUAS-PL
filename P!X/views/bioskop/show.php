<?php require_once 'views/layouts/header.php'; ?>

<div class="container">
    <div class="header-section">
        <h1>🏢 Detail Bioskop</h1>
        <a href="index.php?module=bioskop" class="btn btn-secondary">⬅️ Kembali</a>
    </div>

    <div class="detail-container" style="grid-template-columns: 1fr;">
        <div class="detail-info">
            <div style="display: flex; align-items: center; margin-bottom: 30px;">
                <div style="width: 100px; height: 100px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 48px; margin-right: 25px;">
                    🏢
                </div>
                <div>
                    <h2 style="margin: 0 0 10px 0;"><?php echo htmlspecialchars($bioskopData['nama_bioskop']); ?></h2>
                    <p style="font-size: 18px; color: #01b4e4; margin: 0;">
                        📍 <?php echo htmlspecialchars($bioskopData['kota']); ?>
                    </p>
                </div>
            </div>

            <div class="info-grid">
                <div class="info-item">
                    <strong>📮 Alamat:</strong>
                    <p><?php echo htmlspecialchars($bioskopData['alamat_bioskop']); ?></p>
                </div>

                <div class="info-item">
                    <strong>🎭 Jumlah Studio:</strong>
                    <p><?php echo $bioskopData['jumlah_studio']; ?> Studio</p>
                </div>

                <div class="info-item">
                    <strong>📊 Total Jadwal Tayang:</strong>
                    <p><?php echo $bioskopData['total_jadwal']; ?> Jadwal</p>
                </div>
            </div>

            <?php if(!empty($schedules)): ?>
                <div style="margin-top: 40px;">
                    <h3 style="color: #032541; margin-bottom: 20px;">📅 Jadwal Tayang</h3>
                    
                    <div style="display: grid; gap: 15px;">
                        <?php foreach($schedules as $schedule): ?>
                            <div style="background: #f8f9fa; border-radius: 8px; padding: 20px; display: grid; grid-template-columns: auto 1fr auto; gap: 20px; align-items: center;">
                                <div style="text-align: center; padding: 10px; background: white; border-radius: 8px; min-width: 80px;">
                                    <div style="font-size: 24px; font-weight: 700; color: #032541;">
                                        <?php echo date('d', strtotime($schedule['tanggal_tayang'])); ?>
                                    </div>
                                    <div style="font-size: 12px; color: #666;">
                                        <?php echo date('M Y', strtotime($schedule['tanggal_tayang'])); ?>
                                    </div>
                                </div>

                                <div>
                                    <h4 style="margin: 0 0 8px 0; color: #032541;">
                                        🎬 <?php echo htmlspecialchars($schedule['judul_film']); ?>
                                    </h4>
                                    <p style="margin: 3px 0; color: #666; font-size: 14px;">
                                        🕐 <?php echo date('H:i', strtotime($schedule['jam_mulai'])); ?> - 
                                        <?php echo date('H:i', strtotime($schedule['jam_selesai'])); ?>
                                    </p>
                                    <?php if($schedule['nama_tayang']): ?>
                                        <p style="margin: 3px 0; color: #01b4e4; font-weight: 600; font-size: 14px;">
                                            🎫 <?php echo htmlspecialchars($schedule['nama_tayang']); ?>
                                        </p>
                                    <?php endif; ?>
                                </div>

                                <div style="text-align: right;">
                                    <div style="color: #01b4e4; font-weight: 700; font-size: 16px;">
                                        Rp <?php echo number_format($schedule['harga_tiket'], 0, ',', '.'); ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php else: ?>
                <div style="margin-top: 40px; padding: 40px; background: #f8f9fa; border-radius: 8px; text-align: center;">
                    <p style="color: #666; font-size: 16px;">📅 Belum ada jadwal tayang di bioskop ini</p>
                </div>
            <?php endif; ?>

            <div class="detail-actions" style="margin-top: 30px;">
                <a href="index.php?module=bioskop&action=edit&id=<?php echo $bioskopData['id_bioskop']; ?>" 
                   class="btn btn-warning">✏️ Edit Bioskop</a>
                <a href="index.php?module=jadwal&action=create" 
                   class="btn btn-primary">➕ Tambah Jadwal Tayang</a>
                <a href="index.php?module=bioskop&action=delete&id=<?php echo $bioskopData['id_bioskop']; ?>" 
                   class="btn btn-danger" 
                   onclick="return confirm('Apakah Anda yakin ingin menghapus bioskop ini?')">🗑️ Hapus Bioskop</a>
            </div>
        </div>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>