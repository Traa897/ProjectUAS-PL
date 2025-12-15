<?php require_once 'views/layouts/header_public.php'; ?>

<!-- Hero Landing Page -->
<div style="background: linear-gradient(rgba(3, 37, 65, 0.9), rgba(3, 37, 65, 0.9)), url('https://old.itk.ac.id/wp-content/uploads/2021/09/Belakang-Kiri-1024x576.jpg') center/cover; min-height: 80vh; display: flex; align-items: center; justify-content: center; color: white; text-align: center; padding: 40px 20px;">
    <div style="max-width: 900px;">
        <h1 style="font-size: 72px; margin: 0 0 20px 0; font-weight: 700; text-shadow: 3px 3px 6px rgba(0,0,0,0.5); letter-spacing: 10px;">
            P!X
        </h1>
        <p style="font-size: 28px; margin: 0 0 40px 0; opacity: 0.95; text-shadow: 2px 2px 4px rgba(0,0,0,0.5);">
            Sistem Informasi Bioskop Digital
        </p>
        <p style="font-size: 18px; margin: 0 0 50px 0; opacity: 0.9; line-height: 1.6;">
            Booking tiket bioskop dengan mudah, cepat, dan aman.<br>
            Nikmati pengalaman menonton terbaik di bioskop favorit Anda!
        </p>
        
        <div style="display: flex; gap: 20px; justify-content: center; flex-wrap: wrap;">
            <a href="index.php?module=film" class="btn btn-primary" style="padding: 18px 40px; font-size: 18px; background: linear-gradient(135deg, #01b4e4, #0190b8); border-radius: 50px; box-shadow: 0 8px 24px rgba(1, 180, 228, 0.4);">
                🎬 Jelajahi Film
            </a>
            <a href="index.php?module=auth&action=register" class="btn btn-primary" style="padding: 18px 40px; font-size: 18px; background: linear-gradient(135deg, #f093fb, #f5576c); border-radius: 50px; box-shadow: 0 8px 24px rgba(240, 147, 251, 0.4);">
                📝 Daftar Sekarang
            </a>
        </div>
        
        <div style="margin-top: 60px; padding: 30px; background: rgba(255,255,255,0.1); border-radius: 20px; backdrop-filter: blur(10px);">
            <p style="margin: 0 0 15px 0; font-size: 16px; opacity: 0.9;">✨ Sudah punya akun?</p>
            <a href="index.php?module=auth&action=index" style="color: white; font-weight: 700; font-size: 18px; text-decoration: underline;">
                Login di sini →
            </a>
        </div>
    </div>
</div>

<!-- Features Section -->
<div class="container" style="padding: 80px 40px;">
    <h2 style="text-align: center; font-size: 42px; margin-bottom: 60px; color: #032541;">
        Kenapa Memilih P!X?
    </h2>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 40px;">
        <!-- Feature 1 -->
        <div style="text-align: center; padding: 40px 30px; background: white; border-radius: 20px; box-shadow: 0 8px 24px rgba(0,0,0,0.1); transition: transform 0.3s;" onmouseover="this.style.transform='translateY(-10px)'" onmouseout="this.style.transform='translateY(0)'">
            <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #667eea, #764ba2); border-radius: 50%; margin: 0 auto 25px; display: flex; align-items: center; justify-content: center; font-size: 36px;">
                🎟️
            </div>
            <h3 style="margin: 0 0 15px 0; color: #032541; font-size: 22px;">Booking Mudah</h3>
            <p style="margin: 0; color: #666; line-height: 1.6;">
                Pilih film, pilih jadwal, dan booking tiket hanya dalam hitungan detik.
            </p>
        </div>
        
        <!-- Feature 2 -->
        <div style="text-align: center; padding: 40px 30px; background: white; border-radius: 20px; box-shadow: 0 8px 24px rgba(0,0,0,0.1); transition: transform 0.3s;" onmouseover="this.style.transform='translateY(-10px)'" onmouseout="this.style.transform='translateY(0)'">
            <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #01b4e4, #0190b8); border-radius: 50%; margin: 0 auto 25px; display: flex; align-items: center; justify-content: center; font-size: 36px;">
                💳
            </div>
            <h3 style="margin: 0 0 15px 0; color: #032541; font-size: 22px;">Pembayaran Fleksibel</h3>
            <p style="margin: 0; color: #666; line-height: 1.6;">
                Berbagai metode pembayaran: Transfer, E-Wallet, Kartu Kredit, atau Tunai.
            </p>
        </div>
        
        <!-- Feature 3 -->
        <div style="text-align: center; padding: 40px 30px; background: white; border-radius: 20px; box-shadow: 0 8px 24px rgba(0,0,0,0.1); transition: transform 0.3s;" onmouseover="this.style.transform='translateY(-10px)'" onmouseout="this.style.transform='translateY(0)'">
            <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #f093fb, #f5576c); border-radius: 50%; margin: 0 auto 25px; display: flex; align-items: center; justify-content: center; font-size: 36px;">
                ⚡
            </div>
            <h3 style="margin: 0 0 15px 0; color: #032541; font-size: 22px;">Pre-Sale Tiket</h3>
            <p style="margin: 0; color: #666; line-height: 1.6;">
                Dapatkan tiket lebih awal untuk film favorit Anda sebelum tayang.
            </p>
        </div>
    </div>
</div>

<!-- CTA Section -->
<div style="background: linear-gradient(135deg, #01b4e4, #0190b8); padding: 80px 40px; text-align: center; color: white;">
    <h2 style="margin: 0 0 20px 0; font-size: 42px; text-shadow: 2px 2px 4px rgba(0,0,0,0.3);">
        Siap Menonton Film Favorit?
    </h2>
    <p style="margin: 0 0 40px 0; font-size: 20px; opacity: 0.95;">
        Daftar sekarang dan dapatkan pengalaman booking tiket yang lebih mudah!
    </p>
    <div style="display: flex; gap: 20px; justify-content: center; flex-wrap: wrap;">
        <a href="index.php?module=auth&action=register" class="btn btn-primary" style="padding: 18px 40px; font-size: 18px; background: white; color: #01b4e4; border-radius: 50px; box-shadow: 0 8px 24px rgba(0,0,0,0.2);">
            📝 Daftar Gratis
        </a>
        <a href="index.php?module=film" class="btn btn-primary" style="padding: 18px 40px; font-size: 18px; background: rgba(255,255,255,0.2); backdrop-filter: blur(10px); border-radius: 50px; border: 2px solid white;">
            🎬 Lihat Film
        </a>
    </div>
</div>

<?php require_once 'views/layouts/footer.php'; ?>