<?php require_once 'views/layouts/header.php'; ?>

<div class="container">
    <div class="header-section">
        <h1>Tambah</h1>
        <a href="index.php?module=bioskop" class="btn btn-secondary">Kembali</a>
    </div>

    <?php if(isset($_GET['error'])): ?>
        <div class="alert alert-error">
             <?php echo htmlspecialchars($_GET['error']); ?>
        </div>
    <?php endif; ?>

    <div class="form-container">
        <form method="POST" action="index.php?module=bioskop&action=store" class="movie-form">
            <div class="form-group">
                <label for="nama_bioskop">Nama Bioskop *</label>
                <input type="text" id="nama_bioskop" name="nama_bioskop" required 
                       placeholder="Contoh: CGV Balikpapan Plaza">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="kota">Kota *</label>
                    <select id="kota" name="kota" required>
                        <option value="">Pilih Kota</option>
                        <option value="Balikpapan">Balikpapan</option>
                        <option value="Samarinda">Samarinda</option>
                        <option value="Bontang">Bontang</option>
                        <option value="Kutai Kartanegara">Kutai Kartanegara</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="jumlah_studio">Jumlah Studio *</label>
                    <input type="number" id="jumlah_studio" name="jumlah_studio" required 
                           min="1" placeholder="6" value="4">
                </div>
            </div>

            <div class="form-group">
                <label for="alamat_bioskop">Alamat Lengkap *</label>
                <textarea id="alamat_bioskop" name="alamat_bioskop" rows="3" required 
                          placeholder="Masukkan alamat lengkap bioskop..."></textarea>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="index.php?module=bioskop" class="btn btn-secondary"> Batal</a>
            </div>
        </form>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>