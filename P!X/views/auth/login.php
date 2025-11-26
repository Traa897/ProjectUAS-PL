<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container" style="max-width: 500px; margin-top: 50px;">
    <div class="form-container">
        <h2 style="text-align: center; margin-bottom: 30px;">🎬 Login ke P!X</h2>

        <?php if(isset($error)): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST" action="index.php?module=auth&action=login" class="movie-form">
            <div class="form-group">
                <label>Role</label>
                <select name="role" required style="width: 100%; padding: 12px; border: 2px solid #e0e0e0; border-radius: 5px;">
                    <option value="user">User (Pengguna)</option>
                    <option value="admin">Admin</option>
                </select>
            </div>

            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" required placeholder="Masukkan username">
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required placeholder="Masukkan password">
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary" style="width: 100%;">🔐 Login</button>
            </div>
        </form>

        <p style="text-align: center; margin-top: 20px;">
            Belum punya akun? <a href="index.php?module=auth&action=register" style="color: #01b4e4; font-weight: 600;">Daftar Sekarang</a>
        </p>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>