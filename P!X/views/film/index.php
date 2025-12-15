<?php require_once 'views/layouts/header_public.php'; ?>

<!-- Hero Section -->
<div style="background: linear-gradient(rgba(13, 37, 63, 0.8), rgba(13, 37, 63, 0.8)), url('assets/bakcground film/Belakang-Kiri-1024x576.jpg') center/cover; padding: 100px 20px; color: white; position: relative;">
    <div style="max-width: 1400px; margin: 0 auto; padding: 0 20px;">
        <h1 style="font-size: 52px; margin: 0 0 15px 0; font-weight: 700; text-shadow: 2px 2px 4px rgba(0,0,0,0.5);">Daftar Film</h1>
        <p style="font-size: 22px; margin: 0 0 40px 0; opacity: 0.95; text-shadow: 1px 1px 2px rgba(0,0,0,0.5);">Saksikan Film Terbaik Untuk Anda</p>
        
        <!-- Search Bar -->
        <form method="GET" action="index.php" style="max-width: 700px; display: flex; gap: 0; background: white; border-radius: 50px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.3);">
            <input type="hidden" name="module" value="film">
            <input type="text" name="search" placeholder="Cari film berdasarkan judul..." 
                   value="<?php echo htmlspecialchars($search ?? ''); ?>"
                   style="flex: 1; padding: 18px 30px; border: none; font-size: 16px; outline: none; color: #333;">
            <button type="submit" style="padding: 18px 25px; background: linear-gradient(135deg, #0d95a6, #14b8c9); color: white; border: none; font-weight: 600; cursor: pointer; font-size: 16px; transition: all 0.3s;">
                Cari
            </button>
        </form>
    </div>
</div>

<div class="container" style="max-width: 1400px; margin: 0 auto; padding: 50px 20px;">
    
    <!-- Filter by Genre -->
    <div style="margin-bottom: 40px;">
        <div style="display: flex; gap: 10px; overflow-x: auto; padding-bottom: 10px; scrollbar-width: thin;">
            <a href="index.php?module=film" 
               style="flex-shrink: 0; padding: 10px 20px; background: <?php echo empty($genre_filter) ? '#032541' : '#e0e0e0'; ?>; color: <?php echo empty($genre_filter) ? 'white' : '#666'; ?>; border-radius: 25px; text-decoration: none; font-weight: 600; transition: all 0.3s; white-space: nowrap; font-size: 14px;">
                Semua Genre
            </a>
            <?php foreach($genres as $genre): ?>
                <a href="index.php?module=film&genre=<?php echo $genre['id_genre']; ?>" 
                   style="flex-shrink: 0; padding: 10px 20px; background: <?php echo ($genre_filter == $genre['id_genre']) ? '#032541' : '#e0e0e0'; ?>; color: <?php echo ($genre_filter == $genre['id_genre']) ? 'white' : '#666'; ?>; border-radius: 25px; text-decoration: none; font-weight: 600; transition: all 0.3s; white-space: nowrap; font-size: 14px;">
                    <?php echo htmlspecialchars($genre['nama_genre']); ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Film Horizontal Scroll -->
    <?php if(empty($films)): ?>
        <div style="text-align: center; padding: 80px 20px; background: #f8f9fa; border-radius: 15px;">
            <div style="width: 80px; height: 80px; background: #e0e0e0; border-radius: 50%; margin: 0 auto 20px; display: flex; align-items: center; justify-content: center; font-size: 32px; color: #999;">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="2" y="7" width="20" height="15" rx="2" ry="2"></rect>
                    <polyline points="17 2 12 7 7 2"></polyline>
                </svg>
            </div>
            <h3 style="font-size: 24px; color: #6c757d; margin: 0 0 10px 0;">Tidak ada film ditemukan</h3>
            <p style="color: #999; margin: 0 0 20px 0;">
                <?php if($status_filter != ''): ?>
                    Belum ada film dengan status ini. Coba filter lain atau lihat semua film.
                <?php else: ?>
                    Coba ubah filter atau kata kunci pencarian
                <?php endif; ?>
            </p>
            <a href="index.php?module=film" style="display: inline-block; padding: 12px 30px; background: #032541; color: white; text-decoration: none; border-radius: 25px; font-weight: 600;">
                Reset Filter
            </a>
        </div>
    <?php else: ?>
        <div style="display: flex; gap: 20px; overflow-x: auto; padding-bottom: 20px; scrollbar-width: thin;">
            <?php foreach($films as $film): ?>
                <div style="flex-shrink: 0; width: 180px; background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.1); transition: transform 0.3s;" onmouseover="this.style.transform='translateY(-8px)'" onmouseout="this.style.transform='translateY(0)'">
                    <div style="position: relative;">
                        <img src="<?php echo htmlspecialchars($film['poster_url'] ?? 'https://via.placeholder.com/200x300'); ?>" 
                             alt="<?php echo htmlspecialchars($film['judul_film']); ?>"
                             style="width: 100%; height: 260px; object-fit: cover; display: block;">
                        
                        <?php
                        if(isset($film['status']) && $film['status']) {
                            $query_check = "SELECT MIN(tanggal_tayang) as nearest_date 
                                            FROM Jadwal_Tayang 
                                            WHERE id_film = :id_film 
                                            AND tanggal_tayang >= CURDATE()";
                            $stmt_check = $this->db->prepare($query_check);
                            $stmt_check->bindParam(':id_film', $film['id_film']);
                            $stmt_check->execute();
                            $nearest = $stmt_check->fetch(PDO::FETCH_ASSOC);
                            
                            $today = date('Y-m-d');
                            $nearestDate = $nearest['nearest_date'] ?? '';
                            $selisihHari = floor((strtotime($nearestDate) - strtotime($today)) / 86400);
                            
                            if($selisihHari >= 2):
                        ?>
                            <div style="position: absolute; top: 8px; left: 8px; background: linear-gradient(135deg, #f59e0b, #d97706); color: white; padding: 5px 12px; border-radius: 20px; font-size: 11px; font-weight: 700; box-shadow: 0 2px 8px rgba(0,0,0,0.3); text-transform: uppercase; letter-spacing: 0.5px;">
                                PRE-SALE
                            </div>
                        <?php 
                            endif;
                        }
                        ?>
                        
                        <!-- Rating Badge -->
                        <div style="position: absolute; bottom: -18px; left: 10px; width: 40px; height: 40px; background: #032541; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 3px solid #21d07a; box-shadow: 0 2px 10px rgba(0,0,0,0.3);">
                            <span style="color: white; font-weight: bold; font-size: 12px;"><?php echo number_format($film['rating'] * 10, 0); ?>%</span>
                        </div>
                    </div>
                    
                    <div style="padding: 25px 12px 12px 12px;">
                        <h3 style="margin: 0 0 5px 0; font-size: 14px; color: #032541; font-weight: 700; line-height: 1.3; height: 36px; overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                            <?php echo htmlspecialchars($film['judul_film']); ?>
                        </h3>
                        <p style="margin: 0 0 10px 0; color: #999; font-size: 12px;">
                            <?php echo $film['tahun_rilis']; ?>
                        </p>
                        
                        <a href="index.php?module=film&action=show&id=<?php echo $film['id_film']; ?>" 
                           style="display: block; text-align: center; padding: 8px; background: #032541; color: white; text-decoration: none; border-radius: 6px; font-weight: 600; font-size: 13px; transition: all 0.3s;">
                            Lihat Detail
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once 'views/layouts/footer.php'; ?>