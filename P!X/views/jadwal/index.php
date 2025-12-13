<?php 
// File: views/jadwal/index.php 
require_once 'views/layouts/header.php'; 
?>

<div class="hero-section">
    <div class="hero-content">
        <h1>Jadwal Tayang Film</h1>
        <p>Lihat jadwal penayangan film di seluruh bioskop</p>
    </div>
</div>

<div class="container">
    <?php if(isset($_SESSION['flash'])): ?>
        <div class="alert alert-success">
            ✅ <?php echo htmlspecialchars($_SESSION['flash']); unset($_SESSION['flash']); ?>
        </div>
    <?php endif; ?>

    <div class="header-section">
        <h2> Semua Jadwal (<?php echo count($jadwals); ?>)</h2>
        <?php if(isset($_SESSION['admin_id'])): ?>
            <a href="index.php?module=jadwal&action=create" class="btn btn-primary">Tambah Jadwal</a>
        <?php endif; ?>
    </div>

    <!-- Filter Section -->
    <div style="background: white; padding: 20px; border-radius: 10px; margin-bottom: 30px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
        <h3 style="margin: 0 0 15px 0; color: #032541;">Filter Jadwal</h3>
        <form method="GET" action="index.php">
            <input type="hidden" name="module" value="jadwal">
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-bottom: 15px;">
                <div class="form-group" style="margin: 0;">
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #032541;">📅 Tanggal:</label>
                    <input type="date" name="date" style="width: 100%; padding: 10px; border: 2px solid #e0e0e0; border-radius: 5px;" 
                           value="<?php echo isset($_GET['date']) ? htmlspecialchars($_GET['date']) : ''; ?>">
                </div>

                <div class="form-group" style="margin: 0;">
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #032541;">🎬 Film:</label>
                    <select name="film" style="width: 100%; padding: 10px; border: 2px solid #e0e0e0; border-radius: 5px;">
                        <option value="">Semua Film</option>
                        <?php foreach($films as $film): ?>
                            <option value="<?php echo $film['id_film']; ?>" 
                                <?php echo (isset($_GET['film']) && $_GET['film'] == $film['id_film']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($film['judul_film']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group" style="margin: 0;">
                    <label style="display: block; margin-bottom: 5px; font-weight: 600; color: #032541;">🏢 Bioskop:</label>
                    <select name="bioskop" style="width: 100%; padding: 10px; border: 2px solid #e0e0e0; border-radius: 5px;">
                        <option value="">Semua Bioskop</option>
                        <?php foreach($bioskops as $bioskop): ?>
                            <option value="<?php echo $bioskop['id_bioskop']; ?>"
                                <?php echo (isset($_GET['bioskop']) && $_GET['bioskop'] == $bioskop['id_bioskop']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($bioskop['nama_bioskop']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div style="display: flex; gap: 10px;">
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="index.php?module=jadwal" class="btn btn-secondary">Reset</a>
            </div>
        </form>
    </div>

    <!-- Active Filters Badge -->
    <?php if(isset($_GET['date']) || isset($_GET['film']) || isset($_GET['bioskop'])): ?>
    <div style="background: #f8f9fa; padding: 15px 20px; border-radius: 8px; margin-bottom: 20px; display: flex; align-items: center; gap: 15px; flex-wrap: wrap;">
        <strong style="color: #032541;">Filter Aktif:</strong>
        
        <?php if(isset($_GET['date']) && $_GET['date'] != ''): ?>
        <span style="padding: 5px 15px; background: #01b4e4; color: white; border-radius: 20px; font-size: 14px;">
            📅 <?php echo date('d F Y', strtotime($_GET['date'])); ?>
        </span>
        <?php endif; ?>
        
        <?php if(isset($_GET['film']) && $_GET['film'] != ''): ?>
        <?php 
        $selectedFilm = array_filter($films, function($f) { return $f['id_film'] == $_GET['film']; });
        $selectedFilm = reset($selectedFilm);
        ?>
        <span style="padding: 5px 15px; background: #764ba2; color: white; border-radius: 20px; font-size: 14px;">
            🎬 <?php echo htmlspecialchars($selectedFilm['judul_film'] ?? 'Film'); ?>
        </span>
        <?php endif; ?>
        
        <?php if(isset($_GET['bioskop']) && $_GET['bioskop'] != ''): ?>
        <?php 
        $selectedBioskop = array_filter($bioskops, function($b) { return $b['id_bioskop'] == $_GET['bioskop']; });
        $selectedBioskop = reset($selectedBioskop);
        ?>
        <span style="padding: 5px 15px; background: #f5576c; color: white; border-radius: 20px; font-size: 14px;">
            🏢 <?php echo htmlspecialchars($selectedBioskop['nama_bioskop'] ?? 'Bioskop'); ?>
        </span>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <!-- Empty State -->
    <?php if(empty($jadwals)): ?>
        <div style="background: white; padding: 60px 40px; border-radius: 15px; text-align: center; box-shadow: 0 4px 16px rgba(0,0,0,0.1);">
            <div style="font-size: 80px; margin-bottom: 20px; opacity: 0.3;">📅</div>
            
            <?php if(isset($_GET['date']) || isset($_GET['film']) || isset($_GET['bioskop'])): ?>
                <h2 style="margin: 0 0 15px 0; color: #032541;">❌ Tidak Ada Jadwal Ditemukan</h2>
                <p style="margin: 0 0 30px 0; color: #666;">
                    Tidak ada jadwal yang sesuai dengan filter yang Anda pilih.<br>
                    Coba ubah atau hapus filter untuk melihat jadwal lainnya.
                </p>
                
                <div style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
                    <a href="index.php?module=jadwal" class="btn btn-primary"> Lihat Semua Jadwal</a>
                    <?php if(isset($_SESSION['admin_id'])): ?>
                        <a href="index.php?module=jadwal&action=create" class="btn btn-info"> Tambah Jadwal</a>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <h2 style="margin: 0 0 15px 0; color: #032541;"> Belum Ada Jadwal Tayang</h2>
                <p style="margin: 0 0 30px 0; color: #666;">
                    Saat ini belum ada jadwal tayang yang tersedia di sistem.
                </p>
                
                <?php if(isset($_SESSION['admin_id'])): ?>
                    <a href="index.php?module=jadwal&action=create" class="btn btn-primary">Tambah</a>
                <?php else: ?>
                    <a href="index.php?module=film" class="btn btn-primary"> Lihat Daftar Film</a>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <!-- Display Schedules - SIMPLE DESIGN -->
        <div style="display: grid; gap: 20px;">
            <?php foreach($jadwals as $jadwal): ?>
                <div style="background: white; border-radius: 10px; padding: 25px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); display: grid; grid-template-columns: auto 1fr auto; gap: 25px; align-items: center;">
                    
                    <!-- Time Display -->
                    <div style="text-align: center; padding: 15px; background: #f8f9fa; border-radius: 8px; min-width: 100px;">
                        <div style="font-size: 14px; color: #666; margin-bottom: 5px;">
                            <?php 
                            $hari = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                            echo $hari[date('w', strtotime($jadwal['tanggal_tayang']))]; 
                            ?>
                        </div>
                        <div style="font-size: 28px; font-weight: 700; color: #032541;">
                            <?php echo date('d', strtotime($jadwal['tanggal_tayang'])); ?>
                        </div>
                        <div style="font-size: 14px; color: #666;">
                            <?php echo date('M Y', strtotime($jadwal['tanggal_tayang'])); ?>
                        </div>
                        <div style="font-size: 12px; color: #01b4e4; margin-top: 10px; font-weight: 600;">
                            <?php echo date('H:i', strtotime($jadwal['jam_mulai'])); ?> - <?php echo date('H:i', strtotime($jadwal['jam_selesai'])); ?>
                        </div>
                    </div>

                    <!-- Schedule Info -->
                    <div>
                        <h3 style="margin: 0 0 10px 0; font-size: 22px; color: #032541;">
                            🎬 <?php echo htmlspecialchars($jadwal['judul_film']); ?>
                        </h3>
                        <p style="margin: 5px 0; color: #666; font-size: 16px;">
                            🏢 <?php echo htmlspecialchars($jadwal['nama_bioskop']); ?> - <?php echo htmlspecialchars($jadwal['kota']); ?>
                        </p>
                        <?php if(!empty($jadwal['nama_tayang'])): ?>
                            <p style="margin: 5px 0; color: #01b4e4; font-weight: 600;">
                                🎫 <?php echo htmlspecialchars($jadwal['nama_tayang']); ?>
                            </p>
                        <?php endif; ?>
                        <p style="margin: 5px 0; color: #01b4e4; font-weight: 700; font-size: 18px;">
                            💰 Rp <?php echo number_format($jadwal['harga_tiket'], 0, ',', '.'); ?>
                        </p>
                    </div>

                    <!-- Action Buttons -->
                    <div style="display: flex; flex-direction: column; gap: 8px; min-width: 100px;">
                        <?php if(isset($_SESSION['user_id'])): ?>
                            <a href="index.php?module=transaksi&action=booking&id_jadwal=<?php echo $jadwal['id_tayang']; ?>" 
                               class="btn btn-primary" style="text-align: center; padding: 10px 20px;">
                                🎫 Booking
                            </a>
                        <?php endif; ?>
                        
                        <?php if(isset($_SESSION['admin_id'])): ?>
                            <a href="index.php?module=jadwal&action=edit&id=<?php echo $jadwal['id_tayang']; ?>" 
                               class="btn btn-warning" style="text-align: center; padding: 8px 15px;">
                                 Edit
                            </a>
                            <a href="index.php?module=jadwal&action=delete&id=<?php echo $jadwal['id_tayang']; ?>" 
                               class="btn btn-danger" style="text-align: center; padding: 8px 15px;"
                               onclick="return confirm('Hapus jadwal ini?')">
                                 Hapus
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once 'views/layouts/footer.php'; ?>