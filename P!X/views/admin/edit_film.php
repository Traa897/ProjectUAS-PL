<?php require_once 'views/layouts/header.php'; ?>

<div class="container">
    <div class="header-section">
        <h1>✏️ Edit Film</h1>
        <a href="index.php?module=admin&action=dashboard" class="btn btn-secondary">⬅️ Kembali</a>
    </div>

    <?php if(isset($_GET['error'])): ?>
        <div class="alert alert-error">
            ❌ <?php echo htmlspecialchars($_GET['error']); ?>
        </div>
    <?php endif; ?>

    <div class="form-container">
        <form method="POST" action="index.php?module=admin&action=updateFilm" class="movie-form">
            <input type="hidden" name="id_film" value="<?php echo $this->film->id_film; ?>">

            <div class="form-group">
                <label for="judul_film">Judul Film *</label>
                <input type="text" id="judul_film" name="judul_film" required 
                       value="<?php echo htmlspecialchars($this->film->judul_film); ?>">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="tahun_rilis">Tahun Rilis *</label>
                    <input type="number" id="tahun_rilis" name="tahun_rilis" required 
                           min="1900" max="2100" value="<?php echo $this->film->tahun_rilis; ?>">
                </div>

                <div class="form-group">
                    <label for="durasi_menit">Durasi (menit) *</label>
                    <input type="number" id="durasi_menit" name="durasi_menit" required 
                           min="1" value="<?php echo $this->film->durasi_menit; ?>">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="id_genre">Genre *</label>
                    <select id="id_genre" name="id_genre" required>
                        <option value="">Pilih Genre</option>
                        <?php foreach($genres as $genre): ?>
                            <option value="<?php echo $genre['id_genre']; ?>" 
                                <?php echo ($this->film->id_genre == $genre['id_genre']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($genre['nama_genre']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="rating">Rating (0.0 - 10.0) *</label>
                    <input type="number" id="rating" name="rating" step="0.1" 
                           min="0" max="10" required value="<?php echo $this->film->rating; ?>">
                </div>
            </div>

            <div class="form-group">
                <label for="poster_url">URL Poster</label>
                <input type="url" id="poster_url" name="poster_url" 
                       value="<?php echo htmlspecialchars($this->film->poster_url); ?>">
            </div>

            <div class="form-group">
                <label for="sipnosis">Sinopsis *</label>
                <textarea id="sipnosis" name="sipnosis" rows="5" required><?php echo htmlspecialchars($this->film->sipnosis); ?></textarea>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">💾 Update Film</button>
                <a href="index.php?module=admin&action=dashboard" class="btn btn-secondary">❌ Batal</a>
                <a href="index.php?module=admin&action=deleteFilm&id=<?php echo $this->film->id_film; ?>" 
                   class="btn btn-danger" 
                   onclick="return confirm('Apakah Anda yakin ingin menghapus film ini?')">🗑️ Hapus Film</a>
            </div>
        </form>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>