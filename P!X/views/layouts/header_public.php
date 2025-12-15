<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>P!X - Sistem Bioskop</title>
    <link rel="stylesheet" href="assets/style.css">
    <style>
        .public-navbar {
            background: linear-gradient(to right, #032541 0%, #01b4e4 100%);
            padding: 0;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        .public-nav-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .public-nav-brand {
            font-size: 32px;
            font-weight: bold;
            color: #01b4e4;
            text-decoration: none;
            letter-spacing: 2px;
            background: white;
            padding: 8px 20px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }

        .public-nav-brand:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0,0,0,0.15);
        }

        .public-nav-menu {
            display: flex;
            gap: 5px;
            align-items: center;
        }

        .public-nav-item {
            color: white;
            text-decoration: none;
            font-size: 15px;
            font-weight: 600;
            padding: 10px 20px;
            border-radius: 8px;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .public-nav-item:hover {
            background: rgba(255,255,255,0.15);
            transform: translateY(-2px);
        }

        .public-nav-item.active {
            background: rgba(255,255,255,0.2);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .public-nav-item svg {
            width: 18px;
            height: 18px;
        }

        .public-auth-buttons {
            display: flex;
            gap: 10px;
        }

        .public-btn-login {
            padding: 10px 25px;
            background: rgba(255,255,255,0.1);
            color: white;
            text-decoration: none;
            border-radius: 25px;
            font-weight: 600;
            transition: all 0.3s;
            border: 2px solid rgba(255,255,255,0.3);
        }

        .public-btn-login:hover {
            background: rgba(255,255,255,0.2);
            transform: translateY(-2px);
        }

        .public-btn-register {
            padding: 10px 25px;
            background: white;
            color: #01b4e4;
            text-decoration: none;
            border-radius: 25px;
            font-weight: 700;
            transition: all 0.3s;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }

        .public-btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0,0,0,0.3);
        }

        @media (max-width: 768px) {
            .public-nav-container {
                flex-direction: column;
                gap: 15px;
            }
            .public-nav-menu {
                flex-wrap: wrap;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
<nav class="public-navbar">
    <div class="public-nav-container">
        <a href="index.php?module=film" class="public-nav-brand">P!X</a>
        
        <div class="public-nav-menu">
            <a href="index.php?module=film" class="public-nav-item <?php echo (!isset($_GET['module']) || $_GET['module'] == 'film') ? 'active' : ''; ?>">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="2" y="2" width="20" height="20" rx="2.18" ry="2.18"></rect>
                    <line x1="7" y1="2" x2="7" y2="22"></line>
                    <line x1="17" y1="2" x2="17" y2="22"></line>
                    <line x1="2" y1="12" x2="22" y2="12"></line>
                </svg>
                Film
            </a>
            
            <a href="index.php?module=bioskop" class="public-nav-item <?php echo (isset($_GET['module']) && $_GET['module'] == 'bioskop') ? 'active' : ''; ?>">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                    <polyline points="9 22 9 12 15 12 15 22"></polyline>
                </svg>
                Bioskop
            </a>
            
            <a href="index.php?module=jadwal" class="public-nav-item <?php echo (isset($_GET['module']) && $_GET['module'] == 'jadwal') ? 'active' : ''; ?>">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="16" y1="2" x2="16" y2="6"></line>
                    <line x1="8" y1="2" x2="8" y2="6"></line>
                    <line x1="3" y1="10" x2="21" y2="10"></line>
                </svg>
                Jadwal
            </a>
        </div>

        <div class="public-auth-buttons">
            <a href="index.php?module=auth&action=index" class="public-btn-login">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: inline; vertical-align: middle; margin-right: 5px;">
                    <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path>
                    <polyline points="10 17 15 12 10 7"></polyline>
                    <line x1="15" y1="12" x2="3" y2="12"></line>
                </svg>
                Login
            </a>
            <a href="index.php?module=auth&action=register" class="public-btn-register">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: inline; vertical-align: middle; margin-right: 5px;">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                    <circle cx="8.5" cy="7" r="4"></circle>
                    <line x1="20" y1="8" x2="20" y2="14"></line>
                    <line x1="23" y1="11" x2="17" y2="11"></line>
                </svg>
                Daftar
            </a>
        </div>
    </div>
</nav>

<?php
// Toast notification
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