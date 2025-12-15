<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>P!X - Sistem Bioskop</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<nav class="navbar">
    <div class="nav-container">
        <a href="index.php?module=film" class="nav-brand">P!X</a>
        
        <div class="nav-menu">
            <a href="index.php?module=film">🎬 Film</a>
            <a href="index.php?module=bioskop">🏢 Bioskop</a>
            <a href="index.php?module=jadwal">📅 Jadwal</a>
        </div>

        <div class="nav-right">
            <?php 
            if(session_status() == PHP_SESSION_NONE) session_start();
            
            if(isset($_SESSION['user_id'])): 
            ?>
                <!-- User Menu -->
                <div class="nav-actions">
                    <span class="nav-user">👤 <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                    <a href="index.php?module=user&action=dashboard" class="btn-link">Dashboard</a>
                    <a href="index.php?module=user&action=riwayat" class="btn-link">Riwayat</a>
                    <a href="index.php?module=auth&action=logout" class="btn-link" onclick="return confirm('Yakin ingin logout?')">Logout</a>
                </div>
            <?php elseif(isset($_SESSION['admin_id'])): ?>
                <!-- Admin Quick Link -->
                <div class="nav-actions">
                    <span class="nav-user">⚙️ <?php echo htmlspecialchars($_SESSION['admin_name']); ?></span>
                    <a href="index.php?module=admin&action=dashboard" class="btn-link">Admin Panel</a>
                    <a href="index.php?module=auth&action=logout" class="btn-link" onclick="return confirm('Yakin ingin logout?')">Logout</a>
                </div>
            <?php else: ?>
                <!-- Guest Menu -->
                <div class="nav-actions">
                    <a href="index.php?module=auth&action=index" class="btn-link">Login</a>
                    <a href="index.php?module=auth&action=register" class="btn-link">Daftar</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</nav>

<?php
// Toast notification untuk public
if(isset($_SESSION['flash'])) {
    $msg = htmlspecialchars($_SESSION['flash']);
    echo "<div class=\"toast toast-success\" id=\"toast\">";
    echo "<div class=\"toast-body\">$msg</div>";
    echo "<button class=\"toast-close\" aria-label=\"Tutup\">&times;</button>";
    echo "</div>";
    unset($_SESSION['flash']);
}
if(isset($_GET['error'])) {
    $err = htmlspecialchars($_GET['error']);
    echo "<div class=\"toast toast-error\" id=\"toast\">";
    echo "<div class=\"toast-body\">$err</div>";
    echo "<button class=\"toast-close\" aria-label=\"Tutup\">&times;</button>";
    echo "</div>";
}
?>

<script>
(function(){
    var t = document.getElementById('toast');
    if(!t) return;
    function hideToast(){ t.classList.remove('show'); setTimeout(function(){ t.remove(); }, 400); }
    setTimeout(hideToast, 3500);
    var btn = t.querySelector('.toast-close');
    if(btn) btn.addEventListener('click', hideToast);
    setTimeout(function(){ t.classList.add('show'); }, 50);
})();
</script>