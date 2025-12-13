# Implementasi PDO dalam Sistem P!X

## 1. Koneksi Database dengan PDO
**File**: `config/database.php`

PDO (PHP Data Objects) adalah cara modern untuk menghubungkan PHP dengan database. Seperti membuka pintu ke gudang data.

```php
try {
    $this->conn = new PDO(
        "mysql:host=" . $this->host . ";dbname=" . $this->db_name,
        $this->username,
        $this->password
    );
    $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $exception) {
    echo "Connection error: " . $exception->getMessage();
}
```

**Fungsi**: Membuat koneksi ke database `pix_database` dengan error handling yang aman.

---

## 2. Mengambil Data (Read)
**File**: `models/Film.php` - Method `readAll()`

```php
public function readAll() {
    $query = "SELECT f.*, g.Nama_Genre 
              FROM " . $this->table_name . " f
              LEFT JOIN Genre g ON f.ID_Genre = g.ID_Genre
              ORDER BY f.Judul ASC";
    
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt;
}
```

**Analogi**: Seperti meminta daftar semua film di perpustakaan, lengkap dengan kategorinya.

---

## 3. Query dengan Parameter (Secure)
**File**: `models/Film.php` - Method `readOne()`

```php
public function readOne() {
    $query = "SELECT * FROM " . $this->table_name . " WHERE ID_Film = :id LIMIT 1";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':id', $this->id_film);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
```

**Keamanan**: `bindParam()` mencegah SQL Injection. Parameter `:id` di-sanitasi otomatis sebelum dijalankan.

---

## 4. Insert Data
**File**: `models/Transaksi.php` - Method `create()`

```php
public function create() {
    $query = "INSERT INTO " . $this->table_name . "
              (ID_User, Total_Harga, Status, Tanggal_Transaksi)
              VALUES (:id_user, :total, :status, :tanggal)";
    
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':id_user', $this->id_user);
    $stmt->bindParam(':total', $this->total_harga);
    $stmt->bindParam(':status', $this->status);
    $stmt->bindParam(':tanggal', $this->tanggal_transaksi);
    
    return $stmt->execute();
}
```

**Proses**: Menyimpan transaksi booking tiket ke database dengan binding 4 parameter.

---

## 5. JOIN Multiple Tables
**File**: `controllers/TransaksiController.php` - Method `riwayat()`

```php
$query = "SELECT t.*, dt.Harga, dt.Jumlah_Tiket,
          j.Tanggal_Tayang, j.Jam_Tayang,
          f.Judul, f.Poster_Path,
          b.Nama_Bioskop
          FROM Transaksi t
          JOIN Detail_Transaksi dt ON t.ID_Transaksi = dt.ID_Transaksi
          JOIN Jadwal_Tayang j ON dt.ID_Jadwal = j.ID_Jadwal
          JOIN Film f ON j.ID_Film = f.ID_Film
          JOIN Bioskop b ON j.ID_Bioskop = b.ID_Bioskop
          WHERE t.ID_User = :id_user
          ORDER BY t.Tanggal_Transaksi DESC";

$stmt = $this->db->prepare($query);
$stmt->bindParam(':id_user', $id_user);
$stmt->execute();
```

**Kompleksitas**: Menggabungkan 5 tabel sekaligus untuk menampilkan riwayat transaksi lengkap dengan detail film dan bioskop.

---

## 6. Authentication dengan PDO
**File**: `controllers/AuthController.php` - Method `login()`

```php
$query = "SELECT * FROM User WHERE Email = :email LIMIT 1";
$stmt = $this->db->prepare($query);
$stmt->bindParam(':email', $email);
$stmt->execute();

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify($password, $user['Password'])) {
    // Login berhasil
}
```

**Keamanan Ganda**:
1. `bindParam()` mencegah SQL Injection
2. `password_verify()` memverifikasi password yang di-hash dengan bcrypt

---

## Ringkasan Keuntungan PDO

| Fitur | Manfaat |
|-------|---------|
| **Prepared Statements** | Mencegah SQL Injection |
| **bindParam()** | Sanitasi otomatis input user |
| **Error Handling** | Try-catch untuk debugging |
| **Multi-Database Support** | Bisa MySQL, PostgreSQL, SQLite |
| **Fetch Modes** | FETCH_ASSOC, FETCH_OBJ untuk format data fleksibel |

---

## Kesimpulan

PDO dalam sistem P!X digunakan untuk:
- ✅ Koneksi database yang aman
- ✅ CRUD operations (Create, Read, Update, Delete)
- ✅ JOIN complex queries untuk relasi antar tabel
- ✅ Authentication dengan password hashing
- ✅ Transaction management untuk data consistency
