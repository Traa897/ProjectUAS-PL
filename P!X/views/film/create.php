<?php require_once 'views/layouts/header.php'; ?>

<div class="container">
    <div class="header-section">
        <h1>🎬 Tambah Film Baru</h1>
        <a href="index.php?module=admin&action=dashboard" class="btn btn-secondary">← Kembali</a>
    </div>

    <?php if(isset($_GET['error'])): ?>
        <div class="alert alert-error">
            ❌ <?php echo htmlspecialchars($_GET['error']); ?>
        </div>
    <?php endif; ?>

    <div class="form-container" style="max-width: 700px; margin: 0 auto; background: white; padding: 30px; border-radius: 15px; box-shadow: 0 4px 20px rgba(0,0,0,0.1);">
        <form method="POST" action="index.php?module=film&action=store" class="movie-form">
            <div class="form-group" style="margin-bottom: 20px;">
                <label for="judul_film" style="display: block; margin-bottom: 8px; font-weight: 600; color: #032541;">Judul Film *</label>
                <input type="text" id="judul_film" name="judul_film" required 
                       placeholder="Masukkan judul film"
                       style="width: 100%; padding: 12px; border: 2px solid #e0e0e0; border-radius: 5px; font-size: 14px;">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                <div class="form-group">
                    <label for="tahun_rilis" style="display: block; margin-bottom: 8px; font-weight: 600; color: #032541;">Tahun Rilis *</label>
                    <input type="number" id="tahun_rilis" name="tahun_rilis" required 
                           min="1900" max="2100" placeholder="2024"
                           style="width: 100%; padding: 12px; border: 2px solid #e0e0e0; border-radius: 5px;">
                </div>
                <div class="form-group">
                    <label for="durasi_menit" style="display: block; margin-bottom: 8px; font-weight: 600; color: #032541;">Durasi (menit) *</label>
                    <input type="number" id="durasi_menit" name="durasi_menit" required 
                           min="1" placeholder="120"
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
                            <option value="<?php echo $genre['id_genre']; ?>">
                                <?php echo htmlspecialchars($genre['nama_genre']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="rating" style="display: block; margin-bottom: 8px; font-weight: 600; color: #032541;">Rating (0. 0 - 10.0) *</label>
                    <input type="number" id="rating" name="rating" step="0.1" 
                           min="0" max="10" placeholder="7.5" required
                           style="width: 100%; padding: 12px; border: 2px solid #e0e0e0; border-radius: 5px;">
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 20px;">
                <label for="poster_url" style="display: block; margin-bottom: 8px; font-weight: 600; color: #032541;">URL Poster</label>
                <input type="url" id="poster_url" name="poster_url" 
                       placeholder="https://example.com/poster.jpg"
                       value="https://via.placeholder.com/300x450"
                       style="width: 100%; padding: 12px; border: 2px solid #e0e0e0; border-radius: 5px;">
                <small style="color: #666;">Kosongkan untuk menggunakan poster default</small>
            </div>

            <div class="form-group" style="margin-bottom: 25px;">
                <label for="sipnosis" style="display: block; margin-bottom: 8px; font-weight: 600; color: #032541;">Sinopsis *</label>
                <textarea id="sipnosis" name="sipnosis" rows="5" required
                          placeholder="Masukkan sinopsis film..."
                          style="width: 100%; padding: 12px; border: 2px solid #e0e0e0; border-radius: 5px; resize: vertical;"></textarea>
            </div>

            <div style="display: flex; gap: 15px;">
                <button type="submit" class="btn btn-primary" style="flex: 1; padding: 15px;">💾 Simpan Film</button>
                <a href="index.php?module=admin&action=dashboard" class="btn btn-secondary" style="padding: 15px 30px;">Batal</a>
            </div>
        </form>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>
