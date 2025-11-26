<?php require_once 'views/layouts/header.php'; ?>

<div class="container">
    <div class="header-section">
        <h1>Profil Saya</h1>
        <a href="index.php?module=user&action=dashboard" class="btn btn-secondary">Kembali</a>
    </div>

    <div class="form-container">
        <form method="POST" action="index.php?module=user&action=updateProfile" class="movie-form">
            <div class="form-group">
                <label for="username">Username *</label>
                <input type="text" id="username" name="username" required 
                       value="<?php echo htmlspecialchars($this->user->username); ?>">
            </div>

            <div class="form-group">
                <label for="email">Email *</label>
                <input type="email" id="email" name="email" required 
                       value="<?php echo htmlspecialchars($this->user->email); ?>">
            </div>

            <div class="form-group">
                <label for="nama_lengkap">Nama Lengkap *</label>
                <input type="text" id="nama_lengkap" name="nama_lengkap" required 
                       value="<?php echo htmlspecialchars($this->user->nama_lengkap); ?>">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="no_telpon">No. Telepon</label>
                    <input type="tel" id="no_telpon" name="no_telpon" 
                           value="<?php echo htmlspecialchars($this->user->no_telpon ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="tanggal_lahir">Tanggal Lahir</label>
                    <input type="date" id="tanggal_lahir" name="tanggal_lahir" 
                           value="<?php echo $this->user->tanggal_lahir ?? ''; ?>">
                </div>
            </div>

            <div class="form-group">
                <label for="alamat">Alamat</label>
                <textarea id="alamat" name="alamat" rows="3"><?php echo htmlspecialchars($this->user->alamat ?? ''); ?></textarea>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                <a href="index.php?module=user&action=dashboard" class="btn btn-secondary">❌ Batal</a>
            </div>
        </form>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>