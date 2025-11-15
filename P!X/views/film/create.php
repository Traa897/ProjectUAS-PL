<?php require_once 'views/layouts/header.php'; ?>

<div class="container">
    <div class="header-section">
        <h1>Tambah</h1>
        <a href="index.php?module=film" class="btn btn-secondary">⬅Kembali</a>
    </div>

    <?php if(isset($_GET['error'])): ?>
        <div class="alert alert-error">
             <?php echo htmlspecialchars($_GET['error']); ?>
        </div>
    <?php endif; ?>

    <div class="form-container">
        <form method="POST" action="index.php?module=film&action=store" class="movie-form">
            <div class="form-group">
                <label for="judul_film">Judul Film *</label>
                <input type="text" id="judul_film" name="judul_film" required 
                       placeholder="Masukkan judul film">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="tahun_rilis">Tahun Rilis *</label>
                    <input type="number" id="tahun_rilis" name="tahun_rilis" required 
                           min="1900" max="2100" placeholder="2024">
                </div>

                <div class="form-group">
                    <label for="durasi_menit">Durasi (menit) *</label>
                    <input type="number" id="durasi_menit" name="durasi_menit" required 
                           min="1" placeholder="120">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="id_genre">Genre *</label>
                    <select id="id_genre" name="id_genre" required>
                        <option value="">Pilih Genre</option>
                        <?php foreach($genres as $genre): ?>
                            <option value="<?php echo $genre['id_genre']; ?>">
                                <?php echo htmlspecialchars($genre['nama_genre']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="rating">Rating (0.0 - 10.0) *</label>
                    <input type="number" id="rating" name="rating" step="0.1" 
                           min="0" max="10" placeholder="7.5" required>
                </div>
            </div>

            <div class="form-group">
                <label for="poster_url">URL Poster</label>
                <input type="url" id="poster_url" name="poster_url" 
                       placeholder="https://example.com/poster.jpg"
                       value="https://via.placeholder.com/300x450">
            </div>

            <div class="form-group">
                <label for="sipnosis">Sinopsis</label>
                <textarea id="sipnosis" name="sipnosis" rows="5" 
                          placeholder="Masukkan sinopsis film..."></textarea>
            </div>

            <div class="form-group">
                <label for="aktor_names">Nama Aktor (Multiple - Pisahkan dengan koma)</label>
                <textarea id="aktor_names" name="aktor_names" rows="3" 
                          placeholder="Contoh: Iko Uwais, Reza Rahadian, Tara Basro"></textarea>
                <small style="color: #666; display: block; margin-top: 5px;">
                     Masukkan nama aktor yang dibintangi, pisahkan dengan koma (,)
                </small>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">impan</button>
                <a href="index.php?module=film" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>