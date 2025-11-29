<?php require_once 'views/layouts/header.php'; ?>

<div class="container">
    <div class="header-section">
        <h1>Tambah Jadwal Tayang</h1>
        <a href="index.php?module=jadwal" class="btn btn-secondary">Kembali</a>
    </div>

    <?php if(isset($_GET['error'])): ?>
        <div class="alert alert-error">
            ❌ <?php echo htmlspecialchars($_GET['error']); ?>
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
                            <option value="<?php echo $film['id_film']; ?>">
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
                       placeholder="Contoh: Premiere Night, Weekend Special (Opsional)">
            </div>

            <div class="form-group">
                <label for="tanggal_tayang">Tanggal Tayang *</label>
                <input type="date" id="tanggal_tayang" name="tanggal_tayang" required>
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
                       min="0" step="1000" placeholder="35000">
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="index.php?module=jadwal" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>