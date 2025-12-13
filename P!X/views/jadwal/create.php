<?php require_once 'views/layouts/header.php'; ?>

<div class="container">
    <div class="header-section">
        <h1>➕ Tambah Jadwal Tayang</h1>
        <a href="<?php echo isset($_GET['film']) ? 'index.php?module=admin&action=dashboard' : 'index.php?module=jadwal'; ?>" class="btn btn-secondary">⬅️ Kembali</a>
    </div>

    <?php if(isset($_GET['error'])): ?>
        <div class="alert alert-error">
            ❌ <?php echo htmlspecialchars($_GET['error']); ?>
        </div>
    <?php endif; ?>

    <?php 
    // Pre-fill message jika dari dashboard
    $preselectedFilm = isset($_GET['film']) ? $_GET['film'] : '';
    if($preselectedFilm != ''):
        // Get film name for display
        $filmQuery = "SELECT judul_film FROM Film WHERE id_film = :id_film";
        $filmStmt = $this->db->prepare($filmQuery);
        $filmStmt->bindParam(':id_film', $preselectedFilm);
        $filmStmt->execute();
        $filmInfo = $filmStmt->fetch(PDO::FETCH_ASSOC);
    ?>
        <div style="background: #d4edda; border: 2px solid #c3e6cb; color: #155724; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            <strong>ℹ️ Info:</strong> Anda akan menambahkan jadwal untuk film <strong><?php echo htmlspecialchars($filmInfo['judul_film'] ?? 'Unknown'); ?></strong>
        </div>
    <?php endif; ?>

    <div class="form-container">
        <form method="POST" action="index.php?module=jadwal&action=store" class="movie-form">
            <div class="form-row">
                <div class="form-group">
                    <label for="id_film">Film *</label>
                    <select id="id_film" name="id_film" required>
                        <option value="">Pilih Film</option>
                        <?php foreach($films as $film): ?>
                            <option value="<?php echo $film['id_film']; ?>" 
                                <?php echo ($preselectedFilm == $film['id_film']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($film['judul_film']); ?> (<?php echo $film['tahun_rilis']; ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="id_bioskop">Bioskop *</label>
                    <select id="id_bioskop" name="id_bioskop" required>
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
                       placeholder="Contoh: Premiere Night, Weekend Show, Regular Show, Action Night, Family Time, Superhero Show"> 
                <small style="color: #666;">Opsional - Nama khusus untuk jadwal ini (contoh: "Premiere Night", "Weekend Special")</small>
            </div>

            <div class="form-group">
                <label for="tanggal_tayang">Tanggal Tayang *</label>
                <input type="date" id="tanggal_tayang" name="tanggal_tayang" required 
                       min="<?php echo date('Y-m-d'); ?>"
                       value="<?php echo date('Y-m-d'); ?>">
                <small style="color: #666;">Minimal hari ini: <?php echo date('d F Y'); ?></small>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="jam_mulai">Jam Mulai *</label>
                    <input type="time" id="jam_mulai" name="jam_mulai" required>
                </div>

                <div class="form-group">
                    <label for="jam_selesai">Jam Selesai *</label>
                    <input type="time" id="jam_selesai" name="jam_selesai" required>
                </div>
            </div>

            <div class="form-group">
                <label for="harga_tiket">Harga Tiket (Rp) *</label>
                <input type="number" id="harga_tiket" name="harga_tiket" required 
                       min="0" step="1000" placeholder="35000" value="35000">
                <small style="color: #666;">Harga tiket standar (dapat disesuaikan)</small>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">💾 Simpan Jadwal</button>
                <a href="<?php echo isset($_GET['film']) ? 'index.php?module=admin&action=dashboard' : 'index.php?module=jadwal'; ?>" class="btn btn-secondary">❌ Batal</a>
            </div>
        </form>
    </div>
</div>

<script>
// Auto-calculate jam_selesai based on jam_mulai
document.getElementById('jam_mulai').addEventListener('change', function() {
    const jamMulai = this.value;
    if(jamMulai) {
        // Default durasi 2 jam
        const [hours, minutes] = jamMulai.split(':');
        const endHours = (parseInt(hours) + 2) % 24;
        const jamSelesai = String(endHours).padStart(2, '0') + ':' + minutes;
        document.getElementById('jam_selesai').value = jamSelesai;
    }
});
</script>

<?php require_once 'views/layouts/footer.php'; ?>