<?php require_once 'views/layouts/header.php'; ?>

<div class="container">
    <div class="header-section">
        <h1>📅 Pilih Jadwal Tayang</h1>
        <a href="index.php?module=film&action=show&id=<?php echo $id_film; ?>" class="btn btn-secondary">⬅️ Kembali</a>
    </div>

    <?php if($filmData): ?>
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 30px; border-radius: 10px; margin-bottom: 30px; color: white;">
        <h2 style="margin: 0 0 10px 0;">🎬 <?php echo htmlspecialchars($filmData->judul_film); ?></h2>
        <p style="margin: 0; opacity: 0.9;"><?php echo $filmData->tahun_rilis; ?> • <?php echo $filmData->durasi_menit; ?> menit • Rating: <?php echo $filmData->rating; ?>/10</p>
    </div>
    <?php endif; ?>

    <?php if(empty($jadwals)): ?>
        <div class="empty-state">
            <p>📅 Tidak ada jadwal tayang tersedia untuk film ini</p>
            <a href="index.php?module=film" class="btn btn-primary">Kembali ke Daftar Film</a>
        </div>
    <?php else: ?>
        <div style="display: grid; gap: 20px;">
            <?php foreach($jadwals as $jadwal): ?>
                <div style="background: white; border-radius: 10px; padding: 25px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); display: grid; grid-template-columns: auto 1fr auto; gap: 25px; align-items: center;">
                    <div style="text-align: center; padding: 15px; background: #f8f9fa; border-radius: 8px; min-width: 100px;">
                        <div style="font-size: 28px; font-weight: 700; color: #032541;">
                            <?php echo date('d', strtotime($jadwal['tanggal_tayang'])); ?>
                        </div>
                        <div style="font-size: 14px; color: #666;">
                            <?php echo date('M Y', strtotime($jadwal['tanggal_tayang'])); ?>
                        </div>
                        <div style="font-size: 12px; color: #01b4e4; margin-top: 5px;">
                            <?php 
                            $hari = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                            echo $hari[date('w', strtotime($jadwal['tanggal_tayang']))]; 
                            ?>
                        </div>
                    </div>

                    <div>
                        <h3 style="margin: 0 0 10px 0; font-size: 20px; color: #032541;">
                            🏢 <?php echo htmlspecialchars($jadwal['nama_bioskop']); ?>
                        </h3>
                        <p style="margin: 5px 0; color: #666;">
                            📍 <?php echo htmlspecialchars($jadwal['kota']); ?>
                        </p>
                        <p style="margin: 5px 0; color: #666; font-size: 18px; font-weight: 600;">
                            🕐 <?php echo date('H:i', strtotime($jadwal['jam_mulai'])); ?> - <?php echo date('H:i', strtotime($jadwal['jam_selesai'])); ?> WIB
                        </p>
                        <?php if(!empty($jadwal['nama_tayang'])): ?>
                            <p style="margin: 5px 0; color: #01b4e4; font-weight: 600;">
                                🎫 <?php echo htmlspecialchars($jadwal['nama_tayang']); ?>
                            </p>
                        <?php endif; ?>
                    </div>

                    <div style="text-align: right;">
                        <div style="color: #01b4e4; font-weight: 700; font-size: 22px; margin-bottom: 15px;">
                            Rp <?php echo number_format($jadwal['harga_tiket'], 0, ',', '.'); ?>
                        </div>
                        <a href="index.php?module=transaksi&action=booking&id_jadwal=<?php echo $jadwal['id_tayang']; ?>" 
                           class="btn btn-primary" style="padding: 12px 30px; font-size: 16px;">
                            🎫 Booking
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once 'views/layouts/footer.php'; ?>