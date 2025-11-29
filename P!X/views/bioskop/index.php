<?php require_once 'views/layouts/header.php'; ?>

<div class="hero-section">
    <div class="hero-content">
        <h1>Daftar Bioskop</h1>
        <p>Jaringan Bioskop di Kalimantan Timur</p>
        
        <form method="GET" action="index.php" class="hero-search">
            <input type="hidden" name="module" value="bioskop">
            <input type="text" name="search" placeholder="Cari bioskop atau kota..." 
                   value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
            <button type="submit" class="btn-search">Cari</button>
        </form>
    </div>
</div>

<div class="container">
    <?php if(isset($_GET['message'])): ?>
        <div class="alert alert-success">
             <?php echo htmlspecialchars($_GET['message']); ?>
        </div>
    <?php endif; ?>

    <?php if(isset($_GET['error'])): ?>
        <div class="alert alert-error">
             <?php echo htmlspecialchars($_GET['error']); ?>
        </div>
    <?php endif; ?>

    <div class="header-section">
        <h2>Semua Bioskop (<?php echo count($bioskops); ?>)</h2>
        <div>
            <a href="index.php?module=bioskop&action=create" class="btn btn-primary">Tambah</a>
        </div>
    </div>

    <!-- Filter by City -->
    <div class="status-filter-section">
        <h2>Filter By Kota</h2>
        <div class="status-buttons">
            <a href="index.php?module=bioskop" class="btn <?php echo (!isset($_GET['city'])) ? 'btn-primary' : 'btn-secondary'; ?>">
                Semua Kota
            </a>
            <a href="index.php?module=bioskop&city=Balikpapan" 
               class="btn <?php echo (isset($_GET['city']) && $_GET['city'] == 'Balikpapan') ? 'btn-primary' : 'btn-secondary'; ?>">
                Balikpapan
            </a>
            <a href="index.php?module=bioskop&city=Samarinda" 
               class="btn <?php echo (isset($_GET['city']) && $_GET['city'] == 'Samarinda') ? 'btn-primary' : 'btn-secondary'; ?>">
                Samarinda
            </a>
        </div>
    </div>

    <?php if(empty($bioskops)): ?>
        <div class="empty-state">
            <p>Tidak ada bioskop yang ditemukan</p>
            <a href="index.php?module=bioskop&action=create" class="btn btn-primary">Tambah Bioskop Pertama</a>
        </div>
    <?php else: ?>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px;">
            <?php foreach($bioskops as $bioskop): ?>
                <div style="background: white; border-radius: 10px; padding: 25px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); transition: transform 0.3s;" 
                     onmouseover="this.style.transform='translateY(-5px)'" 
                     onmouseout="this.style.transform='translateY(0)'">
                    <div style="display: flex; align-items: center; margin-bottom: 15px;">
                        <div style="width: 60px; height: 60px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 24px; margin-right: 15px;">
                            🏢
                        </div>
                        <div>
                            <h3 style="font-size: 18px; margin: 0; color: #032541;">
                                <?php echo htmlspecialchars($bioskop['nama_bioskop']); ?>
                            </h3>
                            <p style="font-size: 14px; color: #01b4e4; margin: 5px 0 0 0;">
                                📍 <?php echo htmlspecialchars($bioskop['kota']); ?>
                            </p>
                        </div>
                    </div>
                    
                    <p style="font-size: 14px; color: #666; margin-bottom: 10px;">
                        📮 <?php echo htmlspecialchars($bioskop['alamat_bioskop']); ?>
                    </p>
                    
                    <p style="font-size: 14px; color: #666; margin-bottom: 15px;">
                        🎭 <strong><?php echo $bioskop['jumlah_studio']; ?> Studio</strong>
                    </p>
                    
                    <div style="display: flex; gap: 5px;">
                        <a href="index.php?module=bioskop&action=show&id=<?php echo $bioskop['id_bioskop']; ?>" 
                           class="btn btn-info btn-sm" style="flex: 1;"> Detail</a>
                        <a href="index.php?module=bioskop&action=edit&id=<?php echo $bioskop['id_bioskop']; ?>" 
                           class="btn btn-warning btn-sm">Edit</a>
                        <a href="index.php?module=bioskop&action=delete&id=<?php echo $bioskop['id_bioskop']; ?>" 
                           class="btn btn-danger btn-sm" 
                           onclick="return confirm('Hapus bioskop <?php echo htmlspecialchars($bioskop['nama_bioskop']); ?>?')">Hapus</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once 'views/layouts/footer.php'; ?>