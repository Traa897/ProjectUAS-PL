<?php require_once 'views/layouts/header.php'; ?>

<div class="container">
    <div class="header-section">
        <h1>Editr</h1>
        <a href="index.php?module=aktor" class="btn btn-secondary">Kembali</a>
    </div>

    <?php if(isset($_GET['error'])): ?>
        <div class="alert alert-error">
             <?php echo htmlspecialchars($_GET['error']); ?>
        </div>
    <?php endif; ?>

    <div class="form-container">
        <form method="POST" action="index.php?module=aktor&action=update" class="movie-form">
            <input type="hidden" name="id_aktor" value="<?php echo $this->aktor->id_aktor; ?>">

            <div class="form-group">
                <label for="nama_aktor">Nama Aktor *</label>
                <input type="text" id="nama_aktor" name="nama_aktor" required 
                       value="<?php echo htmlspecialchars($this->aktor->nama_aktor); ?>">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="tanggal_lahir">Tanggal Lahir</label>
                    <input type="date" id="tanggal_lahir" name="tanggal_lahir" 
                           value="<?php echo $this->aktor->tanggal_lahir; ?>">
                </div>

                <div class="form-group">
                    <label for="negara_asal">Negara Asal *</label>
                    <input type="text" id="negara_asal" name="negara_asal" required 
                           value="<?php echo htmlspecialchars($this->aktor->negara_asal); ?>">
                </div>
            </div>

            <div class="form-group">
                <label for="photo_url">URL Photo</label>
                <input type="url" id="photo_url" name="photo_url" 
                       value="<?php echo htmlspecialchars($this->aktor->photo_url); ?>">
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="index.php?module=aktor" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>