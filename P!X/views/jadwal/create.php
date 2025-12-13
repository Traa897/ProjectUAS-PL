<?php require_once 'views/layouts/header.php'; ?>

<div class="container">
    <div class="header-section">
        <h1>Tambah Jadwal Tayang</h1>
        <a href="<?php echo isset($_GET['film']) ? 'index.php?module=admin&action=dashboard' : 'index.php?module=jadwal'; ?>" class="btn btn-secondary">Kembali</a>
    </div>

    <?php if(isset($_GET['error'])): ?>
        <div class="alert alert-error">
            <?php echo htmlspecialchars($_GET['error']); ?>
        </div>
    <?php endif; ?>

    <?php 
    $preselectedFilm = isset($_GET['film']) ? $_GET['film'] : '';
    if($preselectedFilm != ''):
        $filmQuery = "SELECT judul_film FROM Film WHERE id_film = :id_film";
        $filmStmt = $this->db->prepare($filmQuery);
        $filmStmt->bindParam(':id_film', $preselectedFilm);
        $filmStmt->execute();
        $filmInfo = $filmStmt->fetch(PDO::FETCH_ASSOC);
    ?>
        <div style="background: #d4edda; border: 2px solid #c3e6cb; color: #155724; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            <strong>Info:</strong> Anda akan menambahkan jadwal untuk film <strong><?php echo htmlspecialchars($filmInfo['judul_film'] ?? 'Unknown'); ?></strong>
        </div>
    <?php endif; ?>

    <div class="form-container">
        <form method="POST" action="index.php?module=jadwal&action=store" class="movie-form">
            <div class="form-row">
                <div class="form-group">
                    <label for="id_film">Film <span style="color: #dc3545;">*</span></label>
                    <select id="id_film" name="id_film" required style="width: 100%; padding: 12px; border: 2px solid #e0e0e0; border-radius: 5px; font-size: 14px;">
                        <option value="">Pilih Film</option>
                        <?php 
                        // FIXED: Hanya tampilkan film yang BELUM PUNYA JADWAL SAMA SEKALI
                        $filmQuery = "SELECT f.id_film, f.judul_film, f.tahun_rilis 
                                      FROM Film f
                                      WHERE NOT EXISTS (
                                          SELECT 1 FROM Jadwal_Tayang jt 
                                          WHERE jt.id_film = f.id_film
                                      )
                                      ORDER BY f.created_at DESC";
                        $filmStmt = $this->db->prepare($filmQuery);
                        $filmStmt->execute();
                        $availableFilms = $filmStmt->fetchAll(PDO::FETCH_ASSOC);
                        
                        foreach($availableFilms as $film): 
                        ?>
                            <option value="<?php echo $film['id_film']; ?>" 
                                <?php echo ($preselectedFilm == $film['id_film']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($film['judul_film']); ?> (<?php echo $film['tahun_rilis']; ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <small style="color: #666; display: block; margin-top: 5px;">
                        Hanya menampilkan film yang belum memiliki jadwal tayang
                    </small>
                </div>

                <div class="form-group">
                    <label for="id_bioskop">Bioskop <span style="color: #dc3545;">*</span></label>
                    <select id="id_bioskop" name="id_bioskop" required style="width: 100%; padding: 12px; border: 2px solid #e0e0e0; border-radius: 5px; font-size: 14px;">
                        <option value="">Pilih Bioskop</option>
                        <?php foreach($bioskops as $bioskop): ?>
                            <option value="<?php echo $bioskop['id_bioskop']; ?>">
                                <?php echo htmlspecialchars($bioskop['nama_bioskop']); ?> - <?php echo $bioskop['kota']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="nama_tayang">Nama Tayang</label>
                <input type="text" id="nama_tayang" name="nama_tayang" 
                       placeholder="Contoh: Premiere Night, Weekend Show"
                       style="width: 100%; padding: 12px; border: 2px solid #e0e0e0; border-radius: 5px; font-size: 14px;"> 
                <small style="color: #666; display: block; margin-top: 5px;">
                    Opsional - Nama khusus untuk jadwal ini
                </small>
            </div>

            <div class="form-group">
                <label for="tanggal_tayang">Tanggal Tayang <span style="color: #dc3545;">*</span></label>
                <input type="date" id="tanggal_tayang" name="tanggal_tayang" required 
                       min="<?php echo date('Y-m-d'); ?>"
                       value="<?php echo date('Y-m-d'); ?>"
                       style="width: 100%; padding: 12px; border: 2px solid #e0e0e0; border-radius: 5px; font-size: 14px;">
                <small style="color: #666; display: block; margin-top: 5px;">
                    Minimal hari ini: <?php echo date('d F Y'); ?>
                </small>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="jam_mulai">Jam Mulai <span style="color: #dc3545;">*</span></label>
                    <input type="time" id="jam_mulai" name="jam_mulai" required
                           style="width: 100%; padding: 12px; border: 2px solid #e0e0e0; border-radius: 5px; font-size: 14px;">
                </div>

                <div class="form-group">
                    <label for="jam_selesai">Jam Selesai <span style="color: #dc3545;">*</span></label>
                    <input type="time" id="jam_selesai" name="jam_selesai" required
                           style="width: 100%; padding: 12px; border: 2px solid #e0e0e0; border-radius: 5px; font-size: 14px;">
                </div>
            </div>

            <div class="form-group">
                <label for="harga_tiket">Harga Tiket (Rp) <span style="color: #dc3545;">*</span></label>
                <input type="number" id="harga_tiket" name="harga_tiket" required 
                       min="0" step="1000" placeholder="35000" value="35000"
                       style="width: 100%; padding: 12px; border: 2px solid #e0e0e0; border-radius: 5px; font-size: 14px;">
                <small style="color: #666; display: block; margin-top: 5px;">
                    Harga tiket standar (dapat disesuaikan)
                </small>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Simpan Jadwal</button>
                <a href="<?php echo isset($_GET['film']) ? 'index.php?module=admin&action=dashboard' : 'index.php?module=jadwal'; ?>" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
    
    <?php if(empty($availableFilms)): ?>
        <div style="background: #fff3cd; border: 2px solid #ffc107; color: #856404; padding: 20px; border-radius: 8px; margin-top: 20px; text-align: center;">
            <svg width="50" height="50" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-bottom: 10px;">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="8" x2="12" y2="12"></line>
                <line x1="12" y1="16" x2="12.01" y2="16"></line>
            </svg>
            <h3 style="margin: 0 0 10px 0;">Tidak Ada Film yang Perlu Dijadwalkan</h3>
            <p style="margin: 0;">Semua film sudah memiliki jadwal tayang. Silakan tambah film baru terlebih dahulu.</p>
            <a href="index.php?module=admin&action=createFilm" class="btn btn-primary" style="margin-top: 15px; display: inline-block;">
                Tambah Film Baru
            </a>
        </div>
    <?php endif; ?>
</div>

<script>
// Auto-calculate jam_selesai based on jam_mulai
document.getElementById('jam_mulai').addEventListener('change', function() {
    const jamMulai = this.value;
    if(jamMulai) {
        const [hours, minutes] = jamMulai.split(':');
        const endHours = (parseInt(hours) + 2) % 24;
        const jamSelesai = String(endHours).padStart(2, '0') + ':' + minutes;
        document.getElementById('jam_selesai').value = jamSelesai;
    }
});
</script>

<?php require_once 'views/layouts/footer.php'; ?>