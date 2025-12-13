<?php require_once 'views/layouts/header.php'; ?>

<div class="container" style="max-width: 1000px; margin: 0 auto; padding: 20px;">
    <a href="index.php?module=film" class="btn btn-secondary" style="margin-bottom: 20px;">Kembali</a>
    
    <div class="film-detail" style="display: grid; grid-template-columns: 300px 1fr; gap: 40px; background: white; border-radius: 15px; overflow: hidden; box-shadow: 0 10px 40px rgba(0,0,0,0.1);">
        <!-- Poster -->
        <div style="position: relative;">
            <img src="<?php echo htmlspecialchars($filmData['poster_url'] ?? 'https://via.placeholder.com/300x450'); ?>" 
                 alt="<?php echo htmlspecialchars($filmData['judul_film']); ?>"
                 style="width: 100%; height: auto; display: block;">
            
            <!-- Rating Circle -->
            <div style="position: absolute; bottom: 20px; left: 20px; width: 60px; height: 60px; background: #032541; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 4px solid #21d07a;">
                <span style="color: white; font-weight: bold; font-size: 18px;"><?php echo number_format($filmData['rating'] * 10, 0); ?>%</span>
            </div>
        </div>
        
        <!-- Info -->
        <div style="padding: 30px;">
            <h1 style="margin: 0 0 10px 0; color: #032541; font-size: 32px;">
                <?php echo htmlspecialchars($filmData['judul_film']); ?>
            </h1>
            
            <?php 
            // FIXED: Cek status film dengan prioritas
            $query = "SELECT COUNT(*) as count FROM Jadwal_Tayang WHERE id_film = :id_film";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id_film', $filmData['id_film']);
            $stmt->execute();
            $jadwalCount = $stmt->fetch(PDO::FETCH_ASSOC);
            
            $filmStatus = 'tidak_ada_jadwal';
            $nearestDate = null;
            
            if($jadwalCount['count'] > 0) {
                // Cek jadwal terdekat
                $query_nearest = "SELECT MIN(tanggal_tayang) as nearest_date 
                                  FROM Jadwal_Tayang 
                                  WHERE id_film = :id_film 
                                  AND tanggal_tayang >= CURDATE()";
                $stmt_nearest = $this->db->prepare($query_nearest);
                $stmt_nearest->bindParam(':id_film', $filmData['id_film']);
                $stmt_nearest->execute();
                $nearest = $stmt_nearest->fetch(PDO::FETCH_ASSOC);
                $nearestDate = $nearest['nearest_date'] ?? null;
                
                if($nearestDate) {
                    $today = date('Y-m-d');
                    $tomorrow = date('Y-m-d', strtotime('+1 day'));
                    
                    // Hitung selisih hari
                    $selisihHari = floor((strtotime($nearestDate) - strtotime($today)) / 86400);
                    
                    if($selisihHari == 0) {
                        $filmStatus = 'sedang_tayang';
                    } elseif($selisihHari == 1) {
                        $filmStatus = 'besok';
                    } else {
                        $filmStatus = 'presale';
                    }
                }
            }
            ?>
            
            <!-- Status Badge dengan 3 kondisi -->
            <?php if($filmStatus == 'sedang_tayang'): ?>
            <div style="display: inline-block; padding: 8px 20px; background: linear-gradient(135deg, #1e3a8a, #1e40af); color: white; border-radius: 20px; font-size: 14px; font-weight: 600; margin-bottom: 20px; box-shadow: 0 4px 12px rgba(30, 58, 138, 0.4);">
                🔥 SEDANG TAYANG HARI INI
            </div>
            <?php elseif($filmStatus == 'besok'): ?>
            <div style="display: inline-block; padding: 8px 20px; background: linear-gradient(135deg, #764ba2, #667eea); color: white; border-radius: 20px; font-size: 14px; font-weight: 600; margin-bottom: 20px; box-shadow: 0 4px 12px rgba(118, 75, 162, 0.4);">
                ⏭️ TAYANG BESOK
            </div>
            <?php elseif($filmStatus == 'presale'): ?>
            <div style="display: inline-block; padding: 8px 20px; background: linear-gradient(135deg, #f59e0b, #d97706); color: white; border-radius: 20px; font-size: 14px; font-weight: 600; margin-bottom: 20px; box-shadow: 0 4px 12px rgba(245, 158, 11, 0.4);">
                ⚡ PRE-SALE - BOOKING TERSEDIA
            </div>
            <?php endif; ?>
            
            <div class="info-grid" style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px; margin-bottom: 25px;">
                <div style="background: #f8f9fa; padding: 15px; border-radius: 8px;">
                    <strong style="color: #666; font-size: 12px; display: block;">🎭 Genre</strong>
                    <span style="color: #032541; font-size: 16px;"><?php echo htmlspecialchars($filmData['nama_genre']); ?></span>
                </div>
                <div style="background: #f8f9fa; padding: 15px; border-radius: 8px;">
                    <strong style="color: #666; font-size: 12px; display: block;">📅 Tahun Rilis</strong>
                    <span style="color: #032541; font-size: 16px;"><?php echo $filmData['tahun_rilis']; ?></span>
                </div>
                <div style="background: #f8f9fa; padding: 15px; border-radius: 8px;">
                    <strong style="color: #666; font-size: 12px; display: block;">⏱️ Durasi</strong>
                    <span style="color: #032541; font-size: 16px;"><?php echo $filmData['durasi_menit']; ?> menit</span>
                </div>
                <div style="background: #f8f9fa; padding: 15px; border-radius: 8px;">
                    <strong style="color: #666; font-size: 12px; display: block;">⭐ Rating</strong>
                    <span style="color: #032541; font-size: 16px;"><?php echo $filmData['rating']; ?> / 10</span>
                </div>
            </div>
            
            <div style="margin-bottom: 25px;">
                <h3 style="color: #032541; margin: 0 0 10px 0;">📖 Sinopsis</h3>
                <p style="color: #666; line-height: 1.8; margin: 0;">
                    <?php echo nl2br(htmlspecialchars($filmData['sipnosis'] ?? 'Tidak ada sinopsis')); ?>
                </p>
            </div>
            
            <!-- Tombol Booking - FIXED untuk semua status -->
            <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                <?php 
                if(session_status() == PHP_SESSION_NONE) session_start();
                
                // Booking tersedia untuk SEMUA status kecuali tidak_ada_jadwal
                if(isset($_SESSION['user_id']) && in_array($filmStatus, ['sedang_tayang', 'besok', 'presale'])): ?>
                    <a href="index.php?module=transaksi&action=pilihJadwal&id_film=<?php echo $filmData['id_film']; ?>" 
                       class="btn btn-primary" style="padding: 15px 30px; font-size: 16px; <?php echo $filmStatus == 'presale' ? 'background: linear-gradient(135deg, #f59e0b, #d97706);' : ''; ?>">
                        <?php 
                        if($filmStatus == 'sedang_tayang') {
                            echo '🎫 Booking Tiket Sekarang';
                        } elseif($filmStatus == 'besok') {
                            echo '⏭️ Booking untuk Besok';
                        } else {
                            echo '⚡ Pre-Sale Booking';
                        }
                        ?>
                    </a>
                <?php elseif(!isset($_SESSION['user_id']) && !isset($_SESSION['admin_id']) && in_array($filmStatus, ['sedang_tayang', 'besok', 'presale'])): ?>
                    <a href="index.php?module=auth&action=index" 
                       class="btn btn-primary" style="padding: 15px 30px; font-size: 16px;">
                        🔐 Login untuk Booking
                    </a>
                <?php elseif($filmStatus == 'tidak_ada_jadwal'): ?>
                    <div style="padding: 15px 30px; background: #f8d7da; color: #721c24; border-radius: 5px; font-weight: 600;">
                        ⚠️ Belum ada jadwal tayang
                    </div>
                <?php endif; ?>
                
                <?php if(isset($_SESSION['admin_id'])): ?>
                    <a href="index.php?module=admin&action=editFilm&id=<?php echo $filmData['id_film']; ?>" 
                       class="btn btn-warning" style="padding: 15px 20px;"> Edit</a>
                    <a href="index.php?module=admin&action=deleteFilm&id=<?php echo $filmData['id_film']; ?>" 
                       class="btn btn-danger" style="padding: 15px 20px;"
                       onclick="return confirm('Yakin hapus film ini?')">🗑️ Hapus</a>
                <?php endif; ?>
            </div>
            
            <!-- Info tambahan untuk Pre-Sale -->
            <?php if($filmStatus == 'presale' && $nearestDate): ?>
            <div style="margin-top: 20px; padding: 15px; background: #fff3cd; border-radius: 8px; border: 2px solid #ffc107;">
                <strong style="color: #856404; font-size: 14px;">ℹ️ Info Pre-Sale:</strong>
                <p style="margin: 5px 0 0 0; color: #856404; font-size: 13px;">
                    Film ini akan tayang pada <strong><?php echo date('d F Y', strtotime($nearestDate)); ?></strong>. 
                    Anda dapat membeli tiket sekarang untuk penayangan tersebut!
                </p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>