<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="container">
    <h2>Buat Akun (User)</h2>

    <?php if(isset($error)): ?>
        <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="POST" action="index.php?module=auth&action=register" class="form-auth">
        <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" required>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" required>
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-primary">Buat Akun</button>
        </div>
    </form>

    <p>Sudah punya akun? <a href="index.php?module=auth&action=index">Login di sini</a>.</p>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
