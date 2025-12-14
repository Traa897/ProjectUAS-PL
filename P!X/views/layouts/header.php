<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>P!X - Sistem Bioskop</title>
    <link rel="stylesheet" href="assets/style.css">
    <style>
        /* Dropdown Menu Styles */
        .dropdown {
            position: relative;
            display: inline-block;
        }
        
        .dropdown-toggle {
            background: rgba(255,255,255,0.1);
            border: none;
            color: white;
            padding: 8px 12px;
            font-size: 20px;
            cursor: pointer;
            border-radius: 6px;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
        }
        
        .dropdown-toggle:hover {
            background: rgba(255,255,255,0.2);
        }
        
        .dropdown-menu {
            display: none;
            position: absolute;
            right: 0;
            top: 50px;
            background: white;
            min-width: 200px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.15);
            border-radius: 10px;
            z-index: 1000;
            overflow: hidden;
        }
        
        .dropdown-menu.show {
            display: block;
            animation: slideDown 0.3s ease;
        }
        
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .dropdown-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 20px;
            color: #032541;
            text-decoration: none;
            transition: all 0.3s;
            font-weight: 600;
            font-size: 14px;
        }
        
        .dropdown-item:hover {
            background: #f8f9fa;
            color: #01b4e4;
        }
        
        .dropdown-item svg {
            width: 18px;
            height: 18px;
            flex-shrink: 0;
        }
        
        .dropdown-divider {
            height: 1px;
            background: #e0e0e0;
            margin: 5px 0;
        }
    </style>
</head>
<body>
<!-- Navbar -->
<nav class="navbar">
    <div class="nav-container">
        <a href="index.php?module=film" class="nav-brand">P!X</a>
        <div class="nav-actions">
            <div class="nav-menu">
                <?php
                if(session_status() == PHP_SESSION_NONE) session_start();
                
                if(isset($_SESSION['admin_id'])):
                ?>
                    <a href="index.php?module=film">Film</a>
                    <a href="index.php?module=bioskop">Bioskop</a>
                    <a href="index.php?module=jadwal">Jadwal</a>
                    <a href="index.php?module=admin&action=dashboard">Dashboard</a>
                <?php 
                elseif(isset($_SESSION['user_id'])):
                ?>
                    <a href="index.php?module=film">Film</a>
                <?php 
                else:
                ?>
                    <a href="index.php?module=film">Film</a>
                <?php endif; ?>
            </div>
            
            <div class="nav-right">
            <?php
            if(isset($_SESSION['admin_id'])): ?>
                <span class="nav-user">👤 <?php echo htmlspecialchars($_SESSION['admin_username']); ?></span>
                <a href="index.php?module=auth&action=logout" class="btn-link">Logout</a>
            <?php elseif(isset($_SESSION['user_id'])): ?>
                <span class="nav-user">👤 <?php echo htmlspecialchars($_SESSION['user_username']); ?></span>
                
                <!-- Dropdown Menu -->
                <div class="dropdown">
                    <button class="dropdown-toggle" id="dropdownToggle">
                        ⋮
                    </button>
                    <div class="dropdown-menu" id="dropdownMenu">
                        <a href="index.php?module=user&action=riwayat" class="dropdown-item">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                <polyline points="14 2 14 8 20 8"></polyline>
                            </svg>
                            Riwayat Tiket
                        </a>
                        <a href="index.php?module=user&action=profile" class="dropdown-item">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                            Akun Saya
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="index.php?module=auth&action=logout" class="dropdown-item" style="color: #dc3545;">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                <polyline points="16 17 21 12 16 7"></polyline>
                                <line x1="21" y1="12" x2="9" y2="12"></line>
                            </svg>
                            Logout
                        </a>
                    </div>
                </div>
            <?php else: ?>
                <a href="index.php?module=auth&action=index" class="btn-link">Login</a>
                <a href="index.php?module=auth&action=register" class="btn-link">Daftar</a>
            <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

<script>
// Dropdown Toggle
document.addEventListener('DOMContentLoaded', function() {
    const dropdownToggle = document.getElementById('dropdownToggle');
    const dropdownMenu = document.getElementById('dropdownMenu');
    
    if(dropdownToggle && dropdownMenu) {
        dropdownToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            dropdownMenu.classList.toggle('show');
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if(!dropdownToggle.contains(e.target) && !dropdownMenu.contains(e.target)) {
                dropdownMenu.classList.remove('show');
            }
        });
    }
});
</script>

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