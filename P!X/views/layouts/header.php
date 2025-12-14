<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - P!X</title>
    <link rel="stylesheet" href="assets/style.css">
    <style>
        /* Admin Navbar Styles */
        .admin-navbar {
            background: linear-gradient(to right, #032541 0%, #01b4e4 100%);
            padding: 0;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        .admin-nav-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .admin-nav-brand {
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

        .admin-nav-brand:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0,0,0,0.15);
        }

        .admin-nav-menu {
            display: flex;
            gap: 5px;
            align-items: center;
        }

        .admin-nav-item {
            color: white;
            text-decoration: none;
            font-size: 15px;
            font-weight: 600;
            padding: 10px 20px;
            border-radius: 8px;
            transition: all 0.3s;
            position: relative;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .admin-nav-item:hover {
            background: rgba(255,255,255,0.15);
            transform: translateY(-2px);
        }

        .admin-nav-item.active {
            background: rgba(255,255,255,0.2);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .admin-nav-item svg {
            width: 18px;
            height: 18px;
        }

        /* Profile Dropdown */
        .admin-profile-dropdown {
            position: relative;
            display: inline-block;
        }

        .admin-profile-toggle {
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

        .admin-profile-toggle:hover {
            background: rgba(255,255,255,0.2);
            border-color: rgba(255,255,255,0.4);
            transform: translateY(-2px);
        }

        .admin-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 16px;
            border: 2px solid white;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        }

        .admin-profile-info {
            text-align: left;
        }

        .admin-profile-name {
            color: white;
            font-weight: 700;
            font-size: 14px;
            margin: 0;
        }

        .admin-profile-role {
            color: rgba(255,255,255,0.8);
            font-size: 12px;
            margin: 0;
        }

        .admin-dropdown-arrow {
            width: 12px;
            height: 12px;
            stroke: white;
            transition: transform 0.3s;
        }

        .admin-profile-dropdown.show .admin-dropdown-arrow {
            transform: rotate(180deg);
        }

        .admin-dropdown-menu {
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

        .admin-dropdown-menu.show {
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

        .admin-dropdown-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px;
            color: white;
            text-align: center;
        }

        .admin-dropdown-header h3 {
            margin: 0 0 5px 0;
            font-size: 18px;
        }

        .admin-dropdown-header p {
            margin: 0;
            opacity: 0.9;
            font-size: 13px;
        }

        .admin-dropdown-item {
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

        .admin-dropdown-item:hover {
            background: #f8f9fa;
            color: #01b4e4;
            padding-left: 25px;
        }

        .admin-dropdown-item svg {
            width: 20px;
            height: 20px;
            flex-shrink: 0;
        }

        .admin-dropdown-item.danger {
            color: #dc3545;
        }

        .admin-dropdown-item.danger:hover {
            background: #fff5f5;
            color: #c82333;
        }

        /* Badge */
        .badge {
            display: inline-block;
            padding: 4px 10px;
            background: #ffc107;
            color: #333;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 700;
            margin-left: 8px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .admin-nav-container {
                flex-direction: column;
                gap: 15px;
            }

            .admin-nav-menu {
                flex-wrap: wrap;
                justify-content: center;
            }

            .admin-profile-info {
                display: none;
            }
        }
    </style>
</head>
<body>
<!-- Admin Navbar -->
<nav class="admin-navbar">
    <div class="admin-nav-container">
        <a href="index.php?module=admin&action=dashboard" class="admin-nav-brand">P!X Admin</a>
        
        <div style="display: flex; align-items: center; gap: 15px;">
            <div class="admin-nav-menu">
                <a href="index.php?module=admin&action=dashboard" class="admin-nav-item <?php echo (isset($_GET['action']) && $_GET['action'] == 'dashboard') || !isset($_GET['action']) ? 'active' : ''; ?>">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="3" width="7" height="7"></rect>
                        <rect x="14" y="3" width="7" height="7"></rect>
                        <rect x="14" y="14" width="7" height="7"></rect>
                        <rect x="3" y="14" width="7" height="7"></rect>
                    </svg>
                    Dashboard
                </a>
                
                <a href="index.php?module=film" class="admin-nav-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="2" y="7" width="20" height="15" rx="2" ry="2"></rect>
                        <polyline points="17 2 12 7 7 2"></polyline>
                    </svg>
                    Film
                </a>
                
                <a href="index.php?module=bioskop" class="admin-nav-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                        <circle cx="12" cy="10" r="3"></circle>
                    </svg>
                    Bioskop
                </a>
                
                <a href="index.php?module=jadwal" class="admin-nav-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="16" y1="2" x2="16" y2="6"></line>
                        <line x1="8" y1="2" x2="8" y2="6"></line>
                        <line x1="3" y1="10" x2="21" y2="10"></line>
                    </svg>
                    Jadwal
                </a>
                
                <a href="index.php?module=admin&action=kelolaUser" class="admin-nav-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                    Users
                </a>
            </div>
            
            <!-- Profile Dropdown -->
            <div class="admin-profile-dropdown" id="adminProfileDropdown">
                <div class="admin-profile-toggle" id="adminProfileToggle">
                    <div class="admin-avatar">
                        <?php
                        if(session_status() == PHP_SESSION_NONE) session_start();
                        $name = $_SESSION['admin_name'] ?? 'Admin';
                        echo strtoupper(substr($name, 0, 1));
                        ?>
                    </div>
                    <div class="admin-profile-info">
                        <p class="admin-profile-name"><?php echo htmlspecialchars($name); ?></p>
                        <p class="admin-profile-role">Administrator</p>
                    </div>
                    <svg class="admin-dropdown-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="6 9 12 15 18 9"></polyline>
                    </svg>
                </div>
                
                <div class="admin-dropdown-menu" id="adminDropdownMenu">
                    <div class="admin-dropdown-header">
                        <h3><?php echo htmlspecialchars($_SESSION['admin_name'] ?? 'Administrator'); ?></h3>
                        <p><?php echo htmlspecialchars($_SESSION['admin_username'] ?? 'admin'); ?></p>
                    </div>
                    
                    <a href="index.php?module=admin&action=dashboard" class="admin-dropdown-item">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="3" width="7" height="7"></rect>
                            <rect x="14" y="3" width="7" height="7"></rect>
                            <rect x="14" y="14" width="7" height="7"></rect>
                            <rect x="3" y="14" width="7" height="7"></rect>
                        </svg>
                        Dashboard
                    </a>
                    
                    <a href="index.php?module=admin&action=createFilm" class="admin-dropdown-item">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        Tambah Film
                    </a>
                    
                    <a href="index.php?module=jadwal&action=create" class="admin-dropdown-item">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                        </svg>
                        Tambah Jadwal
                    </a>
                    
                    <a href="index.php?module=admin&action=kelolaUser" class="admin-dropdown-item">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                        </svg>
                        Kelola User
                    </a>
                    
                    <a href="index.php?module=film" class="admin-dropdown-item">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                            <polyline points="9 22 9 12 15 12 15 22"></polyline>
                        </svg>
                        Lihat Website
                    </a>
                    
                    <a href="index.php?module=auth&action=logout" class="admin-dropdown-item danger">
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
    const dropdown = document.getElementById('adminProfileDropdown');
    const toggle = document.getElementById('adminProfileToggle');
    const menu = document.getElementById('adminDropdownMenu');
    
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