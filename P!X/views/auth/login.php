<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login ke P!X</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body style="background: white; display: flex; justify-content: center; align-items: center; height: 100vh; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">    

<div style="max-width: 500px; width: 90%; background: white; border-radius: 15px; box-shadow: 0 10px 40px rgba(0,0,0,0.3); overflow: hidden;">
    <!-- Header -->
    <div style="background: linear-gradient(to right, #3299c9ff 0%, #01b4e4 100%); padding: 30px; text-align: center;">
        <h1 style="color: white; margin: 0; font-size: 48px; letter-spacing: 3px;">Login P!X</h1>

    </div>

    <!-- Alert Error -->
    <?php if(isset($error)): ?>
        <div style="background: #ffe6e9; color: #7a1720; padding: 15px; margin: 20px; border-radius: 8px; border: 2px solid #f5c6cb;">
            <strong>❌ <?= htmlspecialchars($error) ?></strong>
        </div>
    <?php endif; ?>

    <!-- Form Login -->
    <form method="POST" action="index.php?module=auth&action=login" style="padding: 30px;">

        <!-- Username -->
        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #032541;">Username</label>
            <input type="text" name="username" required placeholder="Masukkan username"
                   style="width: 100%; padding: 12px 15px; border: 2px solid #e0e0e0; border-radius: 5px; font-size: 14px;">
        </div>

        <!-- Password -->
        <div style="margin-bottom: 25px;">
            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #032541;">Password</label>
            <input type="password" name="password" required placeholder="Masukkan password"
                   style="width: 100%; padding: 12px 15px; border: 2px solid #e0e0e0; border-radius: 5px; font-size: 14px;">
        </div>

        <!-- Submit Button -->
        <button type="submit" style="width: 100%; padding: 15px; background: linear-gradient(to right, #01b4e4, #3ab3e2ff); color: white; border: none; border-radius: 5px; font-size: 16px; font-weight: 600; cursor: pointer; transition: all 0.3s;">
            Login
        </button>

        <!-- Register Link -->
        <p style="text-align: center; margin-top: 20px; color: #666;">
            Belum punya akun? 
            <a href="index.php?module=auth&action=register" style="color: #01b4e4; font-weight: 600; text-decoration: none;">
                Daftar Sekarang
            </a>
        </p>
    </form>
</div>

</body>
</html>