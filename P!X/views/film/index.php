<?php require_once 'views/layouts/header.php'; ?>

<div class="container">
    <div class="header-section">
        <h1>🎬 Daftar Film</h1>
    </div>

    <!-- Filter & Search -->
    <div class="filter-section" style="background: #f8f9fa; padding: 20px; border-radius: 10px; margin-bottom: 30px;">
        <form method="GET" action="index.php" style="display: flex; flex-wrap: wrap; gap: 15px; align-items: center;">
            <input type="hidden" name="module" value="film">
            
            <!-- Search -->
            <div style="flex: 1; min-width: 200px;">
                <input type="text" name="search" placeholder="🔍 Cari judul film..." 
                       value="<?php echo htmlspecialchars($search ??  ''); ?>"
                       style="width: 100%; padding: 10px 15px; border: 2px solid #e0e0e0; border-radius: 5px;">
            </div>
            
            <!-- Genre Filter -->
            <div>
                <select name="genre" style="padding: 10px 15px; border: 2px solid #e0e0e0; border-radius: 5px;">
                    <option value="">Semua Genre</option>
                    <?php foreach($genres as $genre): ?>
                        <option value="<?php echo $genre['id_genre']; ?>" 
                            <?php echo ($genre_filter == $genre['id_genre']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($genre['nama_genre']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <!-- Status Filter -->
            <div>
                <select name="status" style="padding: 10px 15px; border: 2px solid #e0e0e0; border-radius: 5px;">
                    <option value="">Semua Status</option>
                    <option value="akan_tayang" <?php echo ($status_filter == 'akan_tayang') ? 'selected' : ''; ?>>Akan Tayang</option>
                    <option value="sedang_tayang" <?php echo ($status_filter == 'sedang_tayang') ? 'selected' : ''; ?>>Sedang Tayang</option>
                    <option value="telah_tayang" <?php echo ($status_filter == 'telah_tayang') ? 'selected' : ''; ?>>Telah Tayang</option>
                </select>
            </div>
            
            <button type="submit" class="btn btn-primary">Filter</button>
            <a href="index.php?module=film" class="btn btn-secondary">Reset</a>
        </form>
    </div>

    <!-- Status Count Cards -->
    <div class="stats-row" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 15px; margin-bottom: 30px;">
        <div style="background: linear-gradient(135deg, #667eea, #764ba2); color: white; padding: 20px; border-radius: 10px; text-align: center;">
            <h3 style="margin: 0; font-size: 28px;"><?php echo $countAkanTayang; ?></h3>
            <p style="margin: 5px 0 0 0;">Akan Tayang</p>
        </div>
        <div style="background: linear-gradient(135deg, #11998e, #38ef7d); color: white; padding: 20px; border-radius: 10px; text-align: center;">
            <h3 style="margin: 0; font-size: 28px;"><?php echo $countSedangTayang; ?></h3>
            <p style="margin: 5px 0 0 0;">Sedang Tayang</p>
        </div>
        <div style="background: linear-gradient(135deg, #eb3349, #f45c43); color: white; padding: 20px; border-radius: 10px; text-align: center;">
            <h3 style="margin: 0; font-size: 28px;"><?php echo $countTelahTayang; ?></h3>
            <p style="margin: 5px 0 0 0;">Telah Tayang</p>
        </div>
    </div>

    <!-- Film Grid -->
    <?php if(empty($films)): ?>
        <div class="empty-state" style="text-align: center; padding: 50px;">
            <h3>😢 Tidak ada film ditemukan</h3>
            <p>Coba ubah filter atau kata kunci pencarian</p>
        </div>
    <?php else: ?>
        <div class="movie-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 25px;">
            <?php foreach($films as $film): ?>
                <div class="movie-card" style="background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.1); transition: transform 0.3s;">
                    <div style="position: relative;">
                        <img src="<?php echo htmlspecialchars($film['poster_url'] ?? 'https://via.placeholder.com/200x300'); ?>" 
                             alt="<?php echo htmlspecialchars($film['judul_film']); ?>"
                             style="width: 100%; height: 300px; object-fit: cover;">
                        
                        <!-- Rating Badge -->
                        <div style="position: absolute; bottom: -15px; left: 10px; width: 40px; height: 40px; background: #032541; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 3px solid #21d07a;">
                            <span style="color: white; font-weight: bold; font-size: 12px;"><?php echo number_format($film['rating'] * 10, 0); ?>%</span>
                        </div>
                    </div>
                    
                    <div style="padding: 25px 15px 15px 15px;">
                        <h3 style="margin: 0 0 5px 0; font-size: 16px; color: #032541;">
                            <?php echo htmlspecialchars($film['judul_film']); ?>
                        </h3>
                        <p style="margin: 0; color: #666; font-size: 14px;">
                            <?php echo $film['tahun_rilis']; ?> • <?php echo $film['durasi_menit']; ?> menit
                        </p>
                        <p style="margin: 5px 0 0 0; color: #01b4e4; font-size: 13px;">
                            <?php echo htmlspecialchars($film['nama_genre'] ?? 'Unknown'); ?>
                        </p>
                        
                        <a href="index.php?module=film&action=show&id=<?php echo $film['id_film']; ?>" 
                           class="btn btn-primary" style="display: block; text-align: center; margin-top: 15px; padding: 10px;">
                            Lihat Detail
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once 'views/layouts/footer.php'; ?>