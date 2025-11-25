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
                    <a href="index.php?module=bioskop">Bioskop</a>
                    <a href="index.php?module=jadwal">Jadwal</a>
                </div>
                <div class="nav-right">
                <?php
                if(session_status() == PHP_SESSION_NONE) session_start();
                if(isset($_SESSION['user'])): ?>
                    <span class="nav-user"><?php echo htmlspecialchars($_SESSION['user']['username']); ?></span>
                    <span class="nav-user" style="background: #01b4e4; color: white;">
                        <?php echo strtoupper($_SESSION['user']['role']); ?>
                    </span>
                    <a href="index.php?module=auth&action=logout" class="btn btn-link">Logout</a>
                <?php else: ?>
                    <a href="index.php?module=auth&action=index" class="btn btn-link">Login</a>
                <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>
    <?php
    // show flash once
    if(session_status() == PHP_SESSION_NONE) session_start();
    // Render toast notification (flash) or error as toast
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
            // show
            setTimeout(function(){ t.classList.add('show'); }, 50);
        })();
    </script>