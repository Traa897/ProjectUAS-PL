<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container" style="max-width: 600px; margin-top: 50px;">
    <div class="form-container">
        <h2 style="text-align: center; margin-bottom: 30px;">📝 Daftar Akun Baru</h2>

        <?php if(isset($error)): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST" action="index.php?module=auth&action=register" class="movie-form">
            <div class="form-group">
                <label>Username *</label>
                <input type="text" name="username" required placeholder="Pilih username unik">
            </div>

            <div class="form-group">
                <label>Email *</label>
                <input type="email" name="email" required placeholder="email@example.com">
            </div>

            <div class="form-group">
                <label>Password *</label>
                <input type="password" name="password" required placeholder="Minimal 6 karakter" minlength="6">
            </div>

            <div class="form-group">
                <label>Nama Lengkap *</label>
                <input type="text" name="nama_lengkap" required placeholder="Nama lengkap Anda">
            </div>

            <div class="form-group">
                <label>No. Telepon</label>
                <input type="tel" name="no_telpon" placeholder="08xx-xxxx-xxxx">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir">
                </div>

                <div class="form-group">
                    <label>Alamat</label>
                    <textarea name="alamat" rows="2" placeholder="Alamat lengkap (opsional)"></textarea>
                </div>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary" style="width: 100%;">✅ Daftar Sekarang</button>
            </div>
        </form>

        <p style="text-align: center; margin-top: 20px;">
            Sudah punya akun? <a href="index.php?module=auth&action=index" style="color: #01b4e4; font-weight: 600;">Login di sini</a>
        </p>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>