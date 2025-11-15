<?php require_once 'views/layouts/header.php'; ?>

<div class="container">
    <div class="header-section">
        <h1>Edit Film</h1>
        <a href="index.php?module=film" class="btn btn-secondary">⬅Kembali</a>
    </div>

    <?php if(isset($_GET['error'])): ?>
        <div class="alert alert-error">
             <?php echo htmlspecialchars($_GET['error']); ?>
        </div>
    <?php endif; ?>

    <div class="form-container">
        <form method="POST" action="index.php?module=film&action=update" class="movie-form">
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
                <label for="sipnosis">Sinopsis</label>
                <textarea id="sipnosis" name="sipnosis" rows="5"><?php echo htmlspecialchars($this->film->sipnosis); ?></textarea>
            </div>

            <div class="form-group">
                <label for="aktor_names">Nama Aktor (Multiple - Pisahkan dengan koma)</label>
                <?php
                // Get current actors as comma separated string
                $current_aktor_names = [];
                foreach($current_actors as $ca) {
                    // Get actor name from id
                    $query = "SELECT nama_aktor FROM Aktor WHERE id_aktor = :id_aktor";
                    $stmt_aktor = $this->db->prepare($query);
                    $stmt_aktor->bindParam(':id_aktor', $ca['id_aktor']);
                    $stmt_aktor->execute();
                    $aktor_data = $stmt_aktor->fetch(PDO::FETCH_ASSOC);
                    if($aktor_data) {
                        $current_aktor_names[] = $aktor_data['nama_aktor'];
                    }
                }
                $aktor_names_string = implode(', ', $current_aktor_names);
                ?>
                <textarea id="aktor_names" name="aktor_names" rows="3" 
                          placeholder="Contoh: Iko Uwais, Reza Rahadian, Tara Basro"><?php echo htmlspecialchars($aktor_names_string); ?></textarea>
                <small style="color: #666; display: block; margin-top: 5px;">
                     Masukkan nama aktor yang dibintangi, pisahkan dengan koma (,)
                </small>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="index.php?module=film" class="btn btn-secondary"> Batal</a>
            </div>
        </form>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>