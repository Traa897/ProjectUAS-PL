<?php require_once 'views/layouts/header.php'; ?>

<div class="container">
    <div class="header-section">
        <h1>✏️ Edit Film</h1>
        <a href="index.php?module=admin&action=dashboard" class="btn btn-secondary">← Kembali</a>
    </div>

    <?php if(isset($_GET['error'])): ?>
        <div class="alert alert-error">
            ❌ <?php echo htmlspecialchars($_GET['error']); ?>
        </div>
    <?php endif; ?>

    <div class="form-container" style="max-width: 700px; margin: 0 auto; background: white; padding: 30px; border-radius: 15px; box-shadow: 0 4px 20px rgba(0,0,0,0.1);">
        <form method="POST" action="index.php?module=film&action=update" class="movie-form">
            <input type="hidden" name="id_film" value="<?php echo $this->film->id_film; ?>">

            <div class="form-group" style="margin-bottom: 20px;">
                <label for="judul_film" style="display: block; margin-bottom: 8px; font-weight: 600; color: #032541;">Judul Film *</label>
                <input type="text" id="judul_film" name="judul_film" required 
                       value="<?php echo htmlspecialchars($this->film->judul_film); ?>"
                       style="width: 100%; padding: 12px; border: 2px solid #e0e0e0; border-radius: 5px; font-size: 14px;">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                <div class="form-group">
                    <label for="tahun_rilis" style="display: block; margin-bottom: 8px; font-weight: 600; color: #032541;">Tahun Rilis *</label>
                    <input type="number" id="tahun_rilis" name="tahun_rilis" required 
                           min="1900" max="2100" value="<?php echo $this->film->tahun_rilis; ?>"
                           style="width: 100%; padding: 12px; border: 2px solid #e0e0e0; border-radius: 5px;">
                </div>
                <div class="form-group">
                    <label for="durasi_menit" style="display: block; margin-bottom: 8px; font-weight: 600; color: #032541;">Durasi (menit) *</label>
                    <input type="number" id="durasi_menit" name="durasi_menit" required 
                           min="1" value="<?php echo $this->film->durasi_menit; ?>"
                           style="width: 100%; padding: 12px; border: 2px solid #e0e0e0; border-radius: 5px;">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                <div class="form-group">
                    <label for="id_genre" style="display: block; margin-bottom: 8px; font-weight: 600; color: #032541;">Genre *</label>
                    <select id="id_genre" name="id_genre" required
                            style="width: 100%; padding: 12px; border: 2px solid #e0e0e0; border-radius: 5px;">
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
                    <label for="rating" style="display: block; margin-bottom: 8px; font-weight: 600; color: #032541;">Rating (0.0 - 10.0) *</label>
                    <input type="number" id="rating" name="rating" step="0.1" 
                           min="0" max="10" required value="<?php echo $this->film->rating; ?>"
                           style="width: 100%; padding: 12px; border: 2px solid #e0e0e0; border-radius: 5px;">
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 20px;">
                <label for="poster_url" style="display: block; margin-bottom: 8px; font-weight: 600; color: #032541;">URL Poster</label>
                <input type="url" id="poster_url" name="poster_url" 
                       value="<?php echo htmlspecialchars($this->film->poster_url); ?>"
                       style="width: 100%; padding: 12px; border: 2px solid #e0e0e0; border-radius: 5px;">
            </div>

            <div class="form-group" style="margin-bottom: 25px;">
                <label for="sipnosis" style="display: block; margin-bottom: 8px; font-weight: 600; color: #032541;">Sinopsis *</label>
                <textarea id="sipnosis" name="sipnosis" rows="5" required
                          style="width: 100%; padding: 12px; border: 2px solid #e0e0e0; border-radius: 5px; resize: vertical;"><?php echo htmlspecialchars($this->film->sipnosis); ?></textarea>
            </div>

            <div style="display: flex; gap: 15px;">
                <button type="submit" class="btn btn-primary" style="flex: 1; padding: 15px;">💾 Update Film</button>
                <a href="index.php?module=admin&action=dashboard" class="btn btn-secondary" style="padding: 15px 30px;">Batal</a>
                <a href="index.php?module=admin&action=deleteFilm&id=<?php echo $this->film->id_film; ?>" 
                   class="btn btn-danger" style="padding: 15px 20px;"
                   onclick="return confirm('Apakah Anda yakin ingin menghapus film ini?')">🗑️ Hapus</a>
            </div>
        </form>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>