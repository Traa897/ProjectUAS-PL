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
            <div class="nav-actions">
                <div class="nav-menu">
                    <a href="index.php?module=film">Film</a>
                    
                    <?php
                    if(session_status() == PHP_SESSION_NONE) session_start();
                    
                    // Menu untuk Admin
                    if(isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true): ?>
                        <a href="index.php?module=admin&action=dashboard">Dashboard</a>
                        <a href="index.php?module=admin&action=laporan">Laporan</a>
                        <a href="index.php?module=bioskop">Bioskop</a>
                        <a href="index.php?module=jadwal">Jadwal</a>
                        <a href="index.php?module=transaksi">Transaksi</a>
                    <?php endif; ?>
                    
                    <!-- Menu untuk User -->
                    <?php if(isset($_SESSION['user_id']) && (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] === false)): ?>
                        <a href="index.php?module=bioskop">Bioskop</a>
                        <a href="index.php?module=jadwal">Jadwal</a>
                        <a href="index.php?module=user&action=dashboard">Dashboard Saya</a>
                    <?php endif; ?>
                </div>
                <div class="nav-right">
                <?php
                // Display user/admin info
                if(isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true): ?>
                    <span class="nav-user">👤 <?php echo htmlspecialchars($_SESSION['admin_name']); ?></span>
                    <span class="nav-user" style="background: #dc3545; color: white;">
                        🔧 ADMIN
                    </span>
                    <a href="index.php?module=auth&action=logout" class="btn-link">Logout</a>
                <?php elseif(isset($_SESSION['user_id'])): ?>
                    <span class="nav-user">👤 <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                    <span class="nav-user" style="background: #01b4e4; color: white;">
                        USER
                    </span>
                    <a href="index.php?module=auth&action=logout" class="btn-link">Logout</a>
                <?php else: ?>
                    <a href="index.php?module=auth&action=index" class="btn-link">Login</a>
                    <a href="index.php?module=auth&action=register" class="btn-link" style="background: #01b4e4;">Daftar</a>
                <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>
    
    <?php
    // Render toast notification
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