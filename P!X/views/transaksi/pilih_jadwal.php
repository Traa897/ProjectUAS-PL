<?php require_once 'views/layouts/header.php'; ?>

<div class="hero-section">
    <div class="hero-content">
        <h1>Pilih Jadwal Tayang</h1>
        <p>Pilih waktu yang sesuai untuk menonton</p>
    </div>
</div>

<div class="container">
    <div class="header-section">
        <h1> <?php echo htmlspecialchars($filmData->judul_film ?? 'Film'); ?></h1>
        <a href="index.php?module=film&action=show&id=<?php echo $id_film; ?>" class="btn btn-secondary">Kembali</a>
    </div>

    <?php if($filmData): ?>
    <div style="background: linear-gradient(135deg, #3296ddff 0%, #59bff6ff 100%); padding: 30px; border-radius: 10px; margin-bottom: 30px; color: white; box-shadow: 0 8px 24px rgba(50, 150, 221, 0.3);">
        <div style="display: flex; align-items: center; gap: 25px;">
            <img src="<?php echo htmlspecialchars($filmData->poster_url ?? 'https://via.placeholder.com/150x225'); ?>" 
                 alt="<?php echo htmlspecialchars($filmData->judul_film); ?>"
                 style="width: 120px; height: 180px; object-fit: cover; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.3);">
            <div style="flex: 1;">
                <h2 style="margin: 0 0 10px 0;"><?php echo htmlspecialchars($filmData->judul_film); ?></h2>
                <p style="margin: 0; opacity: 0.9; font-size: 16px;">
                    📅 <?php echo $filmData->tahun_rilis; ?> • 
                    ⏱️ <?php echo $filmData->durasi_menit; ?> menit • 
                    ⭐ Rating: <?php echo $filmData->rating; ?>/10
                </p>
                <?php if($filmData->sipnosis): ?>
                <p style="margin: 15px 0 0 0; opacity: 0.9; font-size: 14px; line-height: 1.5;">
                    <?php echo substr(htmlspecialchars($filmData->sipnosis), 0, 200); ?><?php echo strlen($filmData->sipnosis) > 200 ? '...' : ''; ?>
                </p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if(empty($jadwals)): ?>
        <div class="empty-state">
            <p>❌ Tidak ada jadwal tayang tersedia untuk film ini</p>
            <a href="index.php?module=film" class="btn btn-primary">Kembali</a>
        </div>
    <?php else: ?>
        <?php
        // Group jadwal by date
        $jadwalByDate = [];
        foreach($jadwals as $jadwal) {
            $date = $jadwal['tanggal_tayang'];
            if(!isset($jadwalByDate[$date])) {
                $jadwalByDate[$date] = [];
            }
            $jadwalByDate[$date][] = $jadwal;
        }
        
        // Get today's date for comparison
        $today = date('Y-m-d');
        ?>

        <?php foreach($jadwalByDate as $date => $jadwalsOnDate): ?>
            <?php
            // FIXED: Hitung selisih hari dengan benar
            $selisihHari = floor((strtotime($date) - strtotime($today)) / 86400);
            
            // Tentukan status tiket
            $isToday = ($selisihHari == 0);
            $isTomorrow = ($selisihHari == 1);
            $isPresale = ($selisihHari > 1); // Lebih dari 1 hari = Pre-Sale
            ?>

            <div style="margin-bottom: 40px;">
                <!-- Date Header with Status Badge -->
                <div style="background: <?php 
                    echo $isToday ? '#0281AA' : 
                        ($isTomorrow ? 'linear-gradient(135deg, #764ba2, #667eea)' : '#FFE8AD');
                ?>; padding: 20px 30px; border-radius: 10px; margin-bottom: 20px; color: <?php echo ($isToday || $isTomorrow) ? 'white' : '#333'; ?>; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 4px 12px rgba(0,0,0,0.2);">
                    <div>
                        <h3 style="margin: 0 0 5px 0; font-size: 24px;">
                            <?php 
                            $hari = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                            echo $hari[date('w', strtotime($date))]; 
                            ?>, <?php echo date('d F Y', strtotime($date)); ?>
                        </h3>
                        <?php if($isToday): ?>
                            <p style="margin: 0; opacity: 0.9; font-size: 14px;">🔥 Tayang Hari Ini</p>
                        <?php elseif($isTomorrow): ?>
                            <p style="margin: 0; opacity: 0.9; font-size: 14px;">⏭️ Tayang Besok</p>
                        <?php elseif($isPresale): ?>
                            <p style="margin: 0; font-size: 14px;">
                                ⚡ Pre-Sale • <?php echo $selisihHari; ?> hari lagi
                            </p>
                        <?php endif; ?>
                    </div>
                    
                    <?php if($isPresale): ?>
                        <div style="background: rgba(0,0,0,0.1); padding: 12px 25px; border-radius: 30px; border: 2px solid rgba(0,0,0,0.15);">
                            <div style="font-size: 24px; font-weight: 700; text-align: center; color: #333;"><?php echo $selisihHari; ?></div>
                            <div style="font-size: 11px; text-align: center; color: #555;">HARI LAGI</div>
                        </div>
                    <?php elseif($isToday): ?>
                        <div style="background: rgba(255,255,255,0.25); padding: 10px 20px; border-radius: 25px; backdrop-filter: blur(10px); border: 2px solid rgba(255,255,255,0.4);">
                            <span style="font-size: 16px; font-weight: 700;">🔥 HARI INI</span>
                        </div>
                    <?php elseif($isTomorrow): ?>
                        <div style="background: rgba(255,255,255,0.25); padding: 10px 20px; border-radius: 25px; backdrop-filter: blur(10px); border: 2px solid rgba(255,255,255,0.4);">
                            <span style="font-size: 16px; font-weight: 700;">⏭️ BESOK</span>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Info Banner berdasarkan status -->
                <?php if($isToday): ?>
                <div style="background: #d1f2eb; border: 3px solid #1abc9c; border-radius: 10px; padding: 20px; margin-bottom: 20px; box-shadow: 0 4px 12px rgba(26, 188, 156, 0.3);">
                    <div style="display: flex; align-items: center; gap: 15px;">
                        <div style="font-size: 48px;">🔥</div>
                        <div style="flex: 1;">
                            <h4 style="margin: 0 0 8px 0; color: #0e6655; font-size: 20px;">
                                🎬 Film Tayang Hari Ini!
                            </h4>
                            <p style="margin: 0; color: #0e6655; font-size: 14px; line-height: 1.6;">
                                Segera booking tiket sebelum habis! Film ini tayang <strong>hari ini <?php echo date('d F Y', strtotime($date)); ?></strong>.
                            </p>
                        </div>
                    </div>
                </div>
                <?php elseif($isTomorrow): ?>
                <div style="background: #e8daef; border: 3px solid #8e44ad; border-radius: 10px; padding: 20px; margin-bottom: 20px; box-shadow: 0 4px 12px rgba(142, 68, 173, 0.3);">
                    <div style="display: flex; align-items: center; gap: 15px;">
                        <div style="font-size: 48px;">⏭️</div>
                        <div style="flex: 1;">
                            <h4 style="margin: 0 0 8px 0; color: #5b2c6f; font-size: 20px;">
                                🎟️ Film Tayang Besok!
                            </h4>
                            <p style="margin: 0; color: #5b2c6f; font-size: 14px; line-height: 1.6;">
                                Booking sekarang untuk penayangan <strong>besok <?php echo date('d F Y', strtotime($date)); ?></strong>. 
                                Dapatkan kursi terbaik!
                            </p>
                        </div>
                    </div>
                </div>
                <?php elseif($isPresale): ?>
                <div style="background: #FFE8AD; border: 3px solid #d97706; border-radius: 10px; padding: 20px; margin-bottom: 20px; box-shadow: 0 4px 12px rgba(255, 232, 173, 0.3);">
                    <div style="display: flex; align-items: center; gap: 15px;">
                        <div style="font-size: 48px;">⚡</div>
                        <div style="flex: 1;">
                            <h4 style="margin: 0 0 8px 0; color: #856404; font-size: 20px;">
                                🎟️ Tiket Pre-Sale Tersedia!
                            </h4>
                            <p style="margin: 0; color: #856404; font-size: 14px; line-height: 1.6;">
                                Dapatkan tiket lebih awal untuk penayangan <strong><?php echo date('d F Y', strtotime($date)); ?></strong>! 
                                Pesan sekarang sebelum tiket habis. Tiket dapat digunakan pada tanggal tersebut.
                            </p>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Jadwal Cards -->
                <div style="display: grid; gap: 15px;">
                    <?php foreach($jadwalsOnDate as $jadwal): ?>
                        <div style="background: white; border-radius: 10px; padding: 25px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); display: grid; grid-template-columns: auto 1fr auto; gap: 25px; align-items: center; position: relative; overflow: hidden;">
                            
                            <!-- Status Corner Badge -->
                            <?php if($isToday): ?>
                            <div style="position: absolute; top: 10px; right: 10px; background: linear-gradient(135deg, #3160a5ff, #0d72bbff); color: white; padding: 6px 15px; border-radius: 20px; font-size: 11px; font-weight: 700; box-shadow: 0 2px 8px rgba(33, 208, 122, 0.4);">
                                🔥 HARI INI
                            </div>
                            <?php elseif($isTomorrow): ?>
                            <div style="position: absolute; top: 10px; right: 10px; background: linear-gradient(135deg, #764ba2, #667eea); color: white; padding: 6px 15px; border-radius: 20px; font-size: 11px; font-weight: 700; box-shadow: 0 2px 8px rgba(118, 75, 162, 0.4);">
                                ⏭️ BESOK
                            </div>
                            <?php elseif($isPresale): ?>
                            <div style="position: absolute; top: 10px; right: 10px; background: linear-gradient(135deg, #be9e34ff 0%, #d5a514ff 100%); color: white; padding: 6px 15px; border-radius: 20px; font-size: 11px; font-weight: 700; box-shadow: 0 2px 8px rgba(240, 147, 251, 0.4);">
                                ⚡ PRE-SALE
                            </div>
                            <?php endif; ?>

                            <div style="text-align: center; padding: 15px; background: #f8f9fa; border-radius: 8px; min-width: 80px;">
                                <div style="font-size: 28px; font-weight: 700; color: #032541;">
                                    <?php echo date('H:i', strtotime($jadwal['jam_mulai'])); ?>
                                </div>
                                <div style="font-size: 11px; color: #666; margin-top: 5px;">
                                    s/d <?php echo date('H:i', strtotime($jadwal['jam_selesai'])); ?>
                                </div>
                            </div>

                            <div>
                                <h3 style="margin: 0 0 10px 0; font-size: 20px; color: #032541;">
                                    🏢 <?php echo htmlspecialchars($jadwal['nama_bioskop']); ?>
                                </h3>
                                <p style="margin: 5px 0; color: #666; font-size: 15px;">
                                    📍 <?php echo htmlspecialchars($jadwal['kota']); ?>
                                </p>
                                <?php if(!empty($jadwal['nama_tayang'])): ?>
                                    <p style="margin: 5px 0; color: #01b4e4; font-weight: 600;">
                                        🎫 <?php echo htmlspecialchars($jadwal['nama_tayang']); ?>
                                    </p>
                                <?php endif; ?>
                                <?php if($isPresale): ?>
                                    <p style="margin: 8px 0 0 0; padding: 6px 12px; background: #fff3cd; color: #856404; font-weight: 600; font-size: 12px; border-radius: 5px; display: inline-block;">
                                        ⚡ Booking tersedia • <?php echo $selisihHari; ?> hari lagi
                                    </p>
                                <?php elseif($isTomorrow): ?>
                                    <p style="margin: 8px 0 0 0; padding: 6px 12px; background: #e8daef; color: #5b2c6f; font-weight: 600; font-size: 12px; border-radius: 5px; display: inline-block;">
                                        ⏭️ Untuk besok
                                    </p>
                                <?php endif; ?>
                            </div>

                            <div style="text-align: right;">
                                <div style="color: #01b4e4; font-weight: 700; font-size: 22px; margin-bottom: 15px;">
                                    Rp <?php echo number_format($jadwal['harga_tiket'], 0, ',', '.'); ?>
                                </div>
                                <a href="index.php?module=transaksi&action=booking&id_jadwal=<?php echo $jadwal['id_tayang']; ?>" 
                                   class="btn btn-primary" style="padding: 12px 25px; font-size: 15px; <?php echo $isPresale ? 'background: linear-gradient(135deg, #f59e0b, #d97706); border: none;' : ''; ?>">
                                    <?php 
                                    if($isToday) {
                                        echo '🔥 Booking Sekarang';
                                    } elseif($isTomorrow) {
                                        echo '⏭️ Booking untuk Besok';
                                    } else {
                                        echo '⚡ Pre-Sale Booking';
                                    }
                                    ?>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php require_once 'views/layouts/footer.php'; ?>