<?php 
// File: views/jadwal/index.php - FIXED
require_once 'views/layouts/header.php'; 
?>

<div class="hero-section">
    <div class="hero-content">
        <h1>📅 Jadwal Tayang Film</h1>
        <p>Lihat jadwal penayangan film di seluruh bioskop</p>
    </div>
</div>

<div class="container">
    <?php if(isset($_GET['message'])): ?>
        <div class="alert alert-success">
            ✅ <?php echo htmlspecialchars($_GET['message']); ?>
        </div>
    <?php endif; ?>

    <?php if(isset($_GET['error'])): ?>
        <div class="alert alert-error">
            ❌ <?php echo htmlspecialchars($_GET['error']); ?>
        </div>
    <?php endif; ?>

    <div class="header-section">
        <h2>Semua Jadwal (<?php echo count($jadwals); ?>)</h2>
        <div>
            <?php if(isset($_SESSION['admin_id'])): ?>
                <a href="index.php?module=jadwal&action=create" class="btn btn-primary">➕ Tambah Jadwal</a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Filter - FIXED: Ubah datetime-local menjadi date -->
    <div style="background: white; padding: 20px; border-radius: 10px; margin-bottom: 30px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
        <form method="GET" action="index.php" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; align-items: end;">
            <input type="hidden" name="module" value="jadwal">
            
            <div class="form-group" style="margin: 0;">
                <label>Tanggal:</label>
                <input type="date" name="date" style="width: 100%; padding: 10px; border: 2px solid #e0e0e0; border-radius: 5px;" 
                       value="<?php echo isset($_GET['date']) ? htmlspecialchars($_GET['date']) : ''; ?>">
            </div>

            <div class="form-group" style="margin: 0;">
                <label>Film:</label>
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
                <label>Bioskop:</label>
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

            <div>
                <button type="submit" class="btn btn-primary" style="width: 100%;">🔍 Filter</button>
            </div>
        </form>
    </div>

    <?php if(empty($jadwals)): ?>
        <div class="empty-state">
            <p>📅 Tidak ada jadwal tayang yang ditemukan</p>
            <?php if(isset($_SESSION['admin_id'])): ?>
                <a href="index.php?module=jadwal&action=create" class="btn btn-primary">Tambah Jadwal Pertama</a>
            <?php else: ?>
                <a href="index.php?module=film" class="btn btn-primary">Lihat Daftar Film</a>
            <?php endif; ?>
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
                    </div>

                    <div>
                        <h3 style="margin: 0 0 10px 0; font-size: 22px; color: #032541;">
                            🎬 <?php echo htmlspecialchars($jadwal['judul_film'] ?? 'Film Tidak Tersedia'); ?>
                        </h3>
                        <p style="margin: 5px 0; color: #666; font-size: 16px;">
                            🏢 <?php echo htmlspecialchars($jadwal['nama_bioskop'] ?? 'Bioskop Tidak Tersedia'); ?> 
                            <?php if(!empty($jadwal['kota'])): ?>
                                - <?php echo htmlspecialchars($jadwal['kota']); ?>
                            <?php endif; ?>
                        </p>
                        <p style="margin: 5px 0; color: #666;">
                            🕐 <?php echo date('H:i', strtotime($jadwal['jam_mulai'])); ?> - <?php echo date('H:i', strtotime($jadwal['jam_selesai'])); ?>
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

                    <div style="display: flex; flex-direction: column; gap: 5px;">
                        <?php if(isset($_SESSION['user_id'])): ?>
                            <a href="index.php?module=transaksi&action=booking&id_jadwal=<?php echo $jadwal['id_tayang']; ?>" 
                               class="btn btn-primary btn-sm">🎫 Booking</a>
                        <?php endif; ?>
                        
                        <?php if(isset($_SESSION['admin_id'])): ?>
                            <a href="index.php?module=jadwal&action=edit&id=<?php echo $jadwal['id_tayang']; ?>" 
                               class="btn btn-warning btn-sm">✏️ Edit</a>
                            <a href="index.php?module=jadwal&action=delete&id=<?php echo $jadwal['id_tayang']; ?>" 
                               class="btn btn-danger btn-sm" 
                               onclick="return confirm('Hapus jadwal ini?')">🗑️ Hapus</a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once 'views/layouts/footer.php'; ?>