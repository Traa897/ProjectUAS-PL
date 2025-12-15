<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard User - P!X</title>
    <link rel="stylesheet" href="assets/style.css">
    <style>
        .user-navbar {
            background: linear-gradient(to right, #032541 0%, #01b4e4 100%);
            padding: 0;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        .user-nav-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .user-nav-brand {
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

        .user-nav-brand:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0,0,0,0.15);
        }

        .user-nav-menu {
            display: flex;
            gap: 5px;
            align-items: center;
        }

        .user-nav-item {
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

        .user-nav-item:hover {
            background: rgba(255,255,255,0.15);
            transform: translateY(-2px);
        }

        .user-nav-item.active {
            background: rgba(255,255,255,0.2);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .user-nav-item svg {
            width: 18px;
            height: 18px;
        }

        /* Profile Dropdown */
        .user-profile-dropdown {
            position: relative;
            display: inline-block;
        }

        .user-profile-toggle {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 20px;
            background: rgba(255,255,255,0.1);
            border-radius: 25px;
            cursor: pointer;
            transition: all 0.3s;
            border: 2px solid rgba(255,255,255,0.2);
        }

        .user-profile-toggle:hover {
            background: rgba(255,255,255,0.2);
            border-color: rgba(255,255,255,0.4);
            transform: translateY(-2px);
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #01b4e4 0%, #0190b8 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 16px;
            border: 2px solid white;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        }

        .user-profile-info {
            text-align: left;
        }

        .user-profile-name {
            color: white;
            font-weight: 700;
            font-size: 14px;
            margin: 0;
        }

        .user-profile-role {
            color: rgba(255,255,255,0.8);
            font-size: 12px;
            margin: 0;
        }

        .user-dropdown-arrow {
            width: 12px;
            height: 12px;
            stroke: white;
            transition: transform 0.3s;
        }

        .user-profile-dropdown.show .user-dropdown-arrow {
            transform: rotate(180deg);
        }

        .user-dropdown-menu {
            display: none;
            position: absolute;
            right: 0;
            top: 65px;
            background: white;
            min-width: 280px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            border-radius: 15px;
            overflow: hidden;
            z-index: 1000;
        }

        .user-dropdown-menu.show {
            display: block;
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .user-dropdown-header {
            background: linear-gradient(135deg, #01b4e4 0%, #0190b8 100%);
            padding: 20px;
            color: white;
            text-align: center;
        }

        .user-dropdown-header h3 {
            margin: 0 0 5px 0;
            font-size: 18px;
        }

        .user-dropdown-header p {
            margin: 0;
            opacity: 0.9;
            font-size: 13px;
        }

        .user-dropdown-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px 20px;
            color: #032541;
            text-decoration: none;
            transition: all 0.3s;
            font-weight: 600;
            font-size: 14px;
            border-bottom: 1px solid #f0f0f0;
        }

        .user-dropdown-item:hover {
            background: #f8f9fa;
            color: #01b4e4;
            padding-left: 25px;
        }

        .user-dropdown-item svg {
            width: 20px;
            height: 20px;
            flex-shrink: 0;
        }

        .user-dropdown-item.danger {
            color: #dc3545;
        }

        .user-dropdown-item.danger:hover {
            background: #fff5f5;
            color: #c82333;
        }

        @media (max-width: 768px) {
            .user-nav-container {
                flex-direction: column;
                gap: 15px;
            }

            .user-nav-menu {
                flex-wrap: wrap;
                justify-content: center;
            }

            .user-profile-info {
                display: none;
            }
        }
    </style>
</head>
<body>
<nav class="user-navbar">
    <div class="user-nav-container">
        <a href="index.php?module=film" class="user-nav-brand">P!X</a>
        
        <div style="display: flex; align-items: center; gap: 15px;">
            <div class="user-nav-menu">
                <!-- Film -->
                <a href="index.php?module=film" class="user-nav-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="2" y="2" width="20" height="20" rx="2.18" ry="2.18"></rect>
                        <line x1="7" y1="2" x2="7" y2="22"></line>
                        <line x1="17" y1="2" x2="17" y2="22"></line>
                        <line x1="2" y1="12" x2="22" y2="12"></line>
                    </svg>
                    Film
                </a>
                
                <!-- Bioskop -->
                <a href="index.php?module=bioskop" class="user-nav-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                        <polyline points="9 22 9 12 15 12 15 22"></polyline>
                    </svg>
                    Bioskop
                </a>
                
                <!-- Jadwal -->
                <a href="index.php?module=jadwal" class="user-nav-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="16" y1="2" x2="16" y2="6"></line>
                        <line x1="8" y1="2" x2="8" y2="6"></line>
                        <line x1="3" y1="10" x2="21" y2="10"></line>
                    </svg>
                    Jadwal
                </a>
            </div>
            
            <!-- Profile Dropdown -->
            <div class="user-profile-dropdown" id="userProfileDropdown">
                <div class="user-profile-toggle" id="userProfileToggle">
                    <div class="user-avatar">
                        <?php
                        if(session_status() == PHP_SESSION_NONE) session_start();
                        $name = $_SESSION['user_name'] ?? 'User';
                        echo strtoupper(substr($name, 0, 1));
                        ?>
                    </div>
                    <div class="user-profile-info">
                        <p class="user-profile-name"><?php echo htmlspecialchars($name); ?></p>
                        <p class="user-profile-role">Member</p>
                    </div>
                    <svg class="user-dropdown-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="6 9 12 15 18 9"></polyline>
                    </svg>
                </div>
                
                <div class="user-dropdown-menu" id="userDropdownMenu">
                    <div class="user-dropdown-header">
                        <h3><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'User'); ?></h3>
                        <p><?php echo htmlspecialchars($_SESSION['user_username'] ?? 'username'); ?></p>
                    </div>
                    
                    <a href="index.php?module=user&action=dashboard" class="user-dropdown-item">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                        Profil Saya
                    </a>
                    
                    <a href="index.php?module=user&action=riwayat" class="user-dropdown-item">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="1 4 1 10 7 10"></polyline>
                            <polyline points="23 20 23 14 17 14"></polyline>
                            <path d="M20.49 9A9 9 0 0 0 5.64 5.64L1 10m22 4l-4.64 4.36A9 9 0 0 1 3.51 15"></path>
                        </svg>
                        Riwayat Booking
                    </a>
                    
                    <a href="index.php?module=auth&action=logout" class="user-dropdown-item danger" onclick="return confirm('Apakah Anda yakin ingin logout?');">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                            <polyline points="16 17 21 12 16 7"></polyline>
                            <line x1="21" y1="12" x2="9" y2="12"></line>
                        </svg>
                        Logout
                    </a>
                </div>
            </div>
        </div>
    </div>
</nav>

<script>
// Profile Dropdown Toggle
document.addEventListener('DOMContentLoaded', function() {
    const dropdown = document.getElementById('userProfileDropdown');
    const toggle = document.getElementById('userProfileToggle');
    const menu = document.getElementById('userDropdownMenu');
    
    if(toggle && menu) {
        toggle.addEventListener('click', function(e) {
            e.stopPropagation();
            dropdown.classList.toggle('show');
            menu.classList.toggle('show');
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if(!dropdown.contains(e.target)) {
                dropdown.classList.remove('show');
                menu.classList.remove('show');
            }
        });
    }
});
</script>

<?php
// Toast notification untuk user
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