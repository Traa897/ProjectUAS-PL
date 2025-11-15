<?php require_once 'views/layouts/header.php'; ?>

<div class="hero-section">
    <div class="hero-content">
        <h1>Daftar Aktor</h1>
        <p>Koleksi Aktor & Aktris Terbaik Indonesia</p>
        
        <form method="GET" action="index.php" class="hero-search">
            <input type="hidden" name="module" value="aktor">
            <input type="text" name="search" placeholder="Cari aktor berdasarkan nama..." 
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
        <h2>Semua Aktor (<?php echo count($aktors); ?>)</h2>
        <div>
            <a href="index.php?module=aktor&action=create" class="btn btn-primary">Tambah</a>
        </div>
    </div>

    <?php if(empty($aktors)): ?>
        <div class="empty-state">
            <p>Tidak ada aktor yang ditemukan</p>
            <a href="index.php?module=aktor&action=create" class="btn btn-primary">Tambah Aktor Pertama</a>
        </div>
    <?php else: ?>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 20px;">
            <?php foreach($aktors as $aktor): ?>
                <div style="background: white; border-radius: 10px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); text-align: center; transition: transform 0.3s;" 
                     onmouseover="this.style.transform='translateY(-5px)'" 
                     onmouseout="this.style.transform='translateY(0)'">
                    <img src="<?php echo htmlspecialchars($aktor['photo_url'] ?? 'https://via.placeholder.com/150'); ?>" 
                         alt="<?php echo htmlspecialchars($aktor['nama_aktor']); ?>"
                         style="width: 150px; height: 150px; border-radius: 50%; object-fit: cover; margin-bottom: 15px;">
                    
                    <h3 style="font-size: 18px; margin-bottom: 5px; color: #032541;">
                        <?php echo htmlspecialchars($aktor['nama_aktor']); ?>
                    </h3>
                    
                    <p style="font-size: 14px; color: #666; margin-bottom: 5px;">
                         <?php echo htmlspecialchars($aktor['negara_asal']); ?>
                    </p>
                    
                    <p style="font-size: 14px; color: #666; margin-bottom: 15px;">
                         <?php echo $aktor['tanggal_lahir'] ? date('d M Y', strtotime($aktor['tanggal_lahir'])) : 'N/A'; ?>
                    </p>
                    
                    <div style="display: flex; gap: 5px; justify-content: center;">
                        <a href="index.php?module=aktor&action=show&id=<?php echo $aktor['id_aktor']; ?>" 
                           class="btn btn-info btn-sm">👁️</a>
                        <a href="index.php?module=aktor&action=edit&id=<?php echo $aktor['id_aktor']; ?>" 
                           class="btn btn-warning btn-sm"></a>
                        <a href="index.php?module=aktor&action=delete&id=<?php echo $aktor['id_aktor']; ?>" 
                           class="btn btn-danger btn-sm" 
                           onclick="return confirm('Hapus aktor <?php echo htmlspecialchars($aktor['nama_aktor']); ?>?')">🗑️</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once 'views/layouts/footer.php'; ?>