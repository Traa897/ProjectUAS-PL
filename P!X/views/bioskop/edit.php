<?php require_once 'views/layouts/header.php'; ?>

<div class="container">
    <div class="header-section">
        <h1>Edit</h1>
        <a href="index.php?module=bioskop" class="btn btn-secondary">Kembali</a>
    </div>

    <?php if(isset($_GET['error'])): ?>
        <div class="alert alert-error">
             <?php echo htmlspecialchars($_GET['error']); ?>
        </div>
    <?php endif; ?>

    <div class="form-container">
        <form method="POST" action="index.php?module=bioskop&action=update" class="movie-form">
            <input type="hidden" name="id_bioskop" value="<?php echo $this->bioskop->id_bioskop; ?>">

            <div class="form-group">
                <label for="nama_bioskop">Nama Bioskop </label>
                <input type="text" id="nama_bioskop" name="nama_bioskop" required 
                       value="<?php echo htmlspecialchars($this->bioskop->nama_bioskop); ?>">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="kota">Kota </label>
                    <select id="kota" name="kota" required>
                        <option value="Balikpapan" <?php echo ($this->bioskop->kota == 'Balikpapan') ? 'selected' : ''; ?>>Balikpapan</option>
                        <option value="Samarinda" <?php echo ($this->bioskop->kota == 'Samarinda') ? 'selected' : ''; ?>>Samarinda</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="jumlah_studio">Jumlah Studio </label>
                    <input type="number" id="jumlah_studio" name="jumlah_studio" required 
                           min="1" value="<?php echo $this->bioskop->jumlah_studio; ?>">
                </div>
            </div>

            <div class="form-group">
                <label for="alamat_bioskop">Alamat Lengkap </label>
                <textarea id="alamat_bioskop" name="alamat_bioskop" rows="3" required><?php echo htmlspecialchars($this->bioskop->alamat_bioskop); ?></textarea>
            </div>

            <div class="form-group">
                <label for="logo_url">Logo URL</label>
                <input type="text" id="logo_url" name="logo_url" 
                       value="<?php echo htmlspecialchars($this->bioskop->logo_url ?? ''); ?>"
                       placeholder="assets/FOTO BIOSKOP/CGV bpp.png">
                <small style="color: #666;">Gunakan path relatif, contoh: assets/FOTO BIOSKOP/CGV bpp.png (BUKAN path absolut C:\xampp\...)</small>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Update Bioskop</button>
                <a href="index.php?module=bioskop" class="btn btn-secondary"> Batal</a>
            </div>
        </form>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>