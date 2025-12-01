

Perubahan yang perlu dilakukan 
- Dashboard.php perlu diperbaiki fungsi pada website tidak berjalan 
- tabel film - genre (M:N)
- Bikin Role user untuk pembelian film 
- tujuan apps web bioskop ini yaitu harus ada transaksi karna ada tabel bioskop 
- probisnya harus jelas mau kemana aranya supaya tidak membingungkan dengan erdnya 


---- ========================================== --------------------
---- DISINI KALAU MAU LIAT AKUN ADMIN : 
---- USERNAME : admin
---- PASSWORD : admin123


Perbaikan pada dashboard penjualan tiket admin 
dan juga buat keterikatan admin dengan user 

tambah jadwal tayang oleh admin tidak bisa  

perbaikan untuk user tidak dapat mendapatkan jadwal tayang film 

perbaikan pada status pembayaran pada user ada yang eror 

ketika ada film yang telah tayang dan akan tayang tidak tertampilkan 


PERBAIKAN SUDAH BENAR SEMUA 
--------------------------------------------------------



-----------
Penjelasan struktur MVC 
# Fungsi & Peran dalam Struktur MVC P!X

## **📂 Models - Data & Business Logic**

| File | Fungsi & Peran |
|------|----------------|
| **QueryBuilder.php** | Helper query database (SELECT, INSERT, UPDATE, DELETE dengan syntax mudah) |
| **Admin.php** | Login admin, buat akun admin, verifikasi password admin |
| **User.php** | Registrasi user, login user, update profil, cek duplikasi username/email |
| **Genre.php** | Ambil list genre film, hitung jumlah film per genre |
| **Film.php** | CRUD film, cari film, hitung status tayang (Akan/Sedang/Telah Tayang) |
| **Bioskop.php** | CRUD bioskop, cari bioskop by kota, ambil jadwal per bioskop |
| **Jadwal.php** | CRUD jadwal tayang, filter jadwal (tanggal/film/bioskop) |
| **Transaksi.php** | Buat booking, generate kode booking, update status bayar, hitung revenue |
| **DetailTransaksi.php** | Simpan detail tiket, cek kursi terpesan, generate kursi random |

---

## **📂 Controllers - Proses Request & Response**

| File | Fungsi & Peran |
|------|----------------|
| **AuthController.php** | Handle login/logout/register untuk admin & user |
| **FilmController.php** | Tampilkan daftar film, detail film, CRUD film (admin only) |
| **BioskopController.php** | Tampilkan bioskop, detail bioskop, CRUD bioskop (admin only) |
| **JadwalController.php** | Tampilkan jadwal tayang, filter jadwal, CRUD jadwal (admin only) |
| **TransaksiController.php** | Proses booking tiket (pilih jadwal → isi form → simpan → konfirmasi) |
| **UserController.php** | Dashboard user, edit profil, riwayat transaksi, detail tiket |
| **AdminController.php** | Dashboard admin, statistik, kelola film/user/transaksi |

---

## **📂 Views - User Interface**

| Folder | Fungsi & Peran |
|--------|----------------|
| **layouts/** | Template header (navbar) & footer yang dipakai semua halaman |
| **auth/** | Form login & registrasi |
| **film/** | Tampilan daftar film, detail film, form kelola film |
| **bioskop/** | Tampilan daftar bioskop, detail bioskop, form kelola bioskop |
| **jadwal/** | Tampilan jadwal tayang, form kelola jadwal |
| **transaksi/** | Form pilih jadwal, booking tiket, struk e-ticket |
| **user/** | Dashboard user, profil, riwayat tiket |
| **admin/** | Dashboard admin, statistik, kelola film/user/transaksi |

---

## **📂 Config & Assets**

| File | Fungsi & Peran |
|------|----------------|
| **config/database.php** | Koneksi ke database MySQL (host, user, password, database name) |
| **assets/style.css** | Styling tampilan (warna, layout, animasi, responsif) |
| **index.php** | Router utama (terima URL → load controller & method yang sesuai) |
| **pix_database.sql** | Schema database (struktur tabel + data awal) |

---

## **🔄 Alur Kerja MVC**

```
User akses URL
    ↓
index.php (Router)
    ↓
Load Controller yang sesuai
    ↓
Controller panggil Model (ambil/simpan data)
    ↓
Model query ke Database
    ↓
Controller kirim data ke View
    ↓
View render HTML
    ↓
Tampil ke User
```

---

## **👥 Peran Per User Role**

### **User (Pelanggan)**
- Lihat film & jadwal
- Booking tiket
- Bayar & cetak e-ticket
- Lihat riwayat transaksi

### **Admin (Pengelola)**
- Kelola film (tambah/edit/hapus)
- Kelola bioskop & jadwal
- Lihat statistik (pendapatan, film terlaris)
- Kelola user (aktifkan/nonaktifkan)
- Update status pembayaran transaksi

### **Public (Belum Login)**
- Lihat daftar film
- Harus login dulu untuk booking


# Kode Router di index.php

## **📍 Lokasi: `P!X/index.php`**

Ini kode yang **membaca module & action** dari URL:

```php
<?php
// index.php - ROUTER UTAMA

// 1. BACA MODULE & ACTION DARI URL
$module = isset($_GET['module']) ? $_GET['module'] : 'film';
$action = isset($_GET['action']) ? $_GET['action'] : 'index';

// Atau pakai syntax pendek (PHP 7+):
$module = $_GET['module'] ?? 'film';      // Default: film
$action = $_GET['action'] ?? 'index';     // Default: index


// 2. LOAD CONTROLLER SESUAI MODULE
try {
    switch ($module) {
        case 'film':
            require_once 'controllers/FilmController.php';
            $controller = new FilmController();
            break;
            
        case 'bioskop':
            require_once 'controllers/BioskopController.php';
            $controller = new BioskopController();
            break;
            
        case 'jadwal':
            require_once 'controllers/JadwalController.php';
            $controller = new JadwalController();
            break;
            
        case 'auth':
            require_once 'controllers/AuthController.php';
            $controller = new AuthController();
            break;
            
        case 'user':
            require_once 'controllers/UserController.php';
            $controller = new UserController();
            break;
            
        case 'transaksi':
            require_once 'controllers/TransaksiController.php';
            $controller = new TransaksiController();
            break;
            
        case 'admin':
            require_once 'controllers/AdminController.php';
            $controller = new AdminController();
            break;
            
        default:
            // Kalau module tidak dikenal, load FilmController
            require_once 'controllers/FilmController.php';
            $controller = new FilmController();
    }

    // 3. PANGGIL METHOD SESUAI ACTION
    if (method_exists($controller, $action)) {
        $controller->$action();  // Jalankan method
    } else {
        // Kalau method tidak ada, panggil index()
        if (method_exists($controller, 'index')) {
            $controller->index();
        } else {
            throw new Exception("Method '$action' tidak ditemukan");
        }
    }
    
} catch (Exception $e) {
    // Kalau ada error
    $_SESSION['flash'] = 'Error: ' . $e->getMessage();
    header('Location: index.php?module=film');
    exit();
}
?>
```

---

## **🔍 Penjelasan Kode:**

### **Bagian 1: Baca URL**
```php
$module = $_GET['module'] ?? 'film';
$action = $_GET['action'] ?? 'index';
```

**Cara Kerja:**
| URL | `$_GET['module']` | `$_GET['action']` | Hasil |
|-----|-------------------|-------------------|-------|
| `index.php` | `null` | `null` | `module='film'`, `action='index'` |
| `index.php?module=bioskop` | `'bioskop'` | `null` | `module='bioskop'`, `action='index'` |
| `index.php?module=bioskop&action=create` | `'bioskop'` | `'create'` | `module='bioskop'`, `action='create'` |
| `index.php?module=auth&action=login` | `'auth'` | `'login'` | `module='auth'`, `action='login'` |

**Penjelasan `??` (Null Coalescing Operator):**
```php
$module = $_GET['module'] ?? 'film';

// Artinya sama dengan:
if (isset($_GET['module'])) {
    $module = $_GET['module'];
} else {
    $module = 'film';  // Default
}
```

---

### **Bagian 2: Load Controller**
```php
switch ($module) {
    case 'bioskop':
        require_once 'controllers/BioskopController.php';
        $controller = new BioskopController();
        break;
}
```

**Cara Kerja:**
```
Kalau $module = 'bioskop'
   ↓
Load file: controllers/BioskopController.php
   ↓
Buat object: $controller = new BioskopController()
```

---

### **Bagian 3: Panggil Method**
```php
if (method_exists($controller, $action)) {
    $controller->$action();
}
```

**Cara Kerja:**
```
Kalau $action = 'create'
   ↓
Cek: apakah BioskopController punya method create()?
   ↓
Kalau ada: jalankan $controller->create()
   ↓
Kalau tidak ada: jalankan $controller->index()
```

---

## **🎯 Contoh Lengkap:**

### **URL: `index.php?module=bioskop&action=create`**

```php
// 1. BACA URL
$module = 'bioskop';  // dari $_GET['module']
$action = 'create';   // dari $_GET['action']

// 2. LOAD CONTROLLER
switch($module) {
    case 'bioskop':
        require_once 'controllers/BioskopController.php';
        $controller = new BioskopController();
        break;
}

// 3. PANGGIL METHOD
if(method_exists($controller, 'create')) {
    $controller->create();  // Jalankan BioskopController::create()
}
```

**Yang Terjadi:**
```
BioskopController::create() dijalankan
   ↓
Load views/bioskop/create.php
   ↓
Tampilkan form tambah bioskop
```

---

## **📂 Struktur File yang Dibaca:**

```
P!X/
├── index.php ← ROUTER (baca module & action)
│
├── controllers/
│   ├── BioskopController.php ← Load kalau module=bioskop
│   ├── FilmController.php ← Load kalau module=film
│   ├── AuthController.php ← Load kalau module=auth
│   └── ...
│
└── views/
    ├── bioskop/
    │   ├── index.php ← Tampil kalau action=index
    │   ├── create.php ← Tampil kalau action=create
    │   └── edit.php ← Tampil kalau action=edit
    └── ...
```

---

## **💡 Kesimpulan:**

**Kode yang baca module & action:**
```php
$module = $_GET['module'] ?? 'film';
$action = $_GET['action'] ?? 'index';
```

**Lokasinya:** `P!X/index.php` (file paling atas)

**Fungsinya:**
1. Baca URL
2. Tentukan Controller mana yang di-load
3. Tentukan Method mana yang dijalankan