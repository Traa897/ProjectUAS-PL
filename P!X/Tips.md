JENIS RELASI PADA TABEL 
```


| No  | Relasi                               | Jenis Relasi             | Penjelasan                                                                                                                                                        |
| --- | ------------------------------------ | ------------------------ | ----------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| 1️⃣ | `genres` → `movies`                  | **One to Many (1 : N)**  | Satu genre bisa memiliki banyak film, tapi setiap film hanya punya satu genre. <br>Relasi lewat kolom `movies.genre_id` → `genres.id`                             |
| 2️⃣ | `movies` → `jadwal_films`            | **One to Many (1 : N)**  | Satu film bisa punya banyak jadwal tayang di berbagai bioskop. <br>Relasi lewat kolom `jadwal_films.movie_id` → `movies.id`                                       |
| 3️⃣ | `bioskops` → `jadwal_films`          | **One to Many (1 : N)**  | Satu bioskop bisa menayangkan banyak film (jadwal berbeda). <br>Relasi lewat kolom `jadwal_films.bioskop_id` → `bioskops.id`                                      |
| 4️⃣ | `aktors` ↔ `movies` *(opsional)*     | **Many to Many (M : N)** | Satu aktor bisa bermain di banyak film, dan satu film bisa punya banyak aktor. <br>Biasanya dibuat lewat tabel penghubung `movie_aktors` (`movie_id`, `aktor_id`) |
| 5️⃣ | `movies` ↔ `genres` ↔ `jadwal_films` | **Relasi beran**_        |                                                                                                                                                                   |

```


------------------------

MVC itu pola arsitektur untuk memisahkan kode jadi 3 bagian supaya rapi dan mudah di-kelola.
M — Model       
Bagian yang ngurus data
 (ambil data dari database, validasi, logic perhitungan).     
V — View      
Bagian yang tampil ke user
 (HTML, tampilan, UI).        
C — Controller        
Bagian yang ngatur alurnya
 (menerima request, memanggil Model, ngirim data ke View).
Contoh alurnya:
User klik tombol → Controller jalan → Controller minta data ke Model → Controller kirim hasil ke View → View tampilkan ke user.        
Singkatnya:
 Model (data) – View (tampilan) – Controller (penghubung).

—-------------------------------------------------------------------------------------------------------------


✔️ 1. $database = new Database();
Membuat objek Database.
 Artinya kamu sedang memanggil class Database untuk dipakai.

✔️ 2. $this->db = $database->getConnection();
Mengambil koneksi database dari objek Database tadi, lalu disimpan ke variabel $this->db
 → supaya bisa dipakai di method lain dalam class itu.

✔️ 3. $this->aktor = new Aktor($this->db);
Membuat objek Aktor, dan langsung memberikan koneksi database ke objek tersebut.
Artinya class Aktor butuh koneksi database untuk query.

__construct itu fungsi khusus dalam OOP PHP yang akan otomatis dijalankan saat objek dibuat.

**LOGIKA BISNIS (Business Logic)** dan **MANIPULASI DATA** dalam konsep Model MVC adalah dua aspek fundamental yang membedakan Model dari komponen lainnya:

## 🎯 **LOGIKA BISNIS (Business Logic)**

### **Apa itu Logika Bisnis?**
Aturan-aturan spesifik dari domain aplikasi yang menentukan **"cara bisnis beroperasi"**.

### **Contoh dalam Sistem Bioskop:**

#### **1. Validasi Bisnis**
```php
// Dalam Film.php
class Film {
    public function validateRating($rating) {
        // Logika bisnis: Rating harus antara 1-10
        return $rating >= 1 && $rating <= 10;
    }
    
    public function validateDuration($duration) {
        // Logika bisnis: Film minimal 60 menit, maksimal 240 menit
        return $duration >= 60 && $duration <= 240;
    }
}
```

#### **2. Aturan Harga**
```php
// Dalam Jadwal.php
class Jadwal {
    public function calculatePrice($film, $bioskop, $waktu) {
        $basePrice = $film->base_price;
        
        // Logika bisnis: Harga weekend lebih mahal
        if ($this->isWeekend($waktu)) {
            $basePrice *= 1.2; // +20%
        }
        
        // Logika bisnis: Bioskop premium charge lebih
        if ($bioskop->type == 'premium') {
            $basePrice *= 1.5; // +50%
        }
        
        return $basePrice;
    }
}
```

#### **3. Aturan Penjadwalan**
```php
// Dalam Bioskop.php
class Bioskop {
    public function canScheduleFilm($film, $jadwalBaru) {
        // Logika bisnis: Cek konflik jadwal
        $existingSchedules = $this->getJadwalHariIni();
        
        foreach ($existingSchedules as $jadwal) {
            if ($this->isTimeConflict($jadwal, $jadwalBaru)) {
                return false; // Tidak boleh ada jadwal bentrok
            }
        }
        return true;
    }
}
```

## 🗃️ **MANIPULASI DATA (Data Manipulation)**

### **Apa itu Manipulasi Data?**
Operasi teknis yang berhubungan langsung dengan **penyimpanan dan pengambilan data**.

### **Contoh dalam Sistem Bioskop:**

#### **1. CRUD Operations**
```php
// Dalam Film.php
class Film {
    public function save() {
        // Manipulasi data: Simpan ke database
        $query = "INSERT INTO films (title, duration, genre_id) VALUES (?, ?, ?)";
        return QueryBuilder::execute($query, [
            $this->title, 
            $this->duration, 
            $this->genre_id
        ]);
    }
    
    public static function find($id) {
        // Manipulasi data: Ambil dari database
        $query = "SELECT * FROM films WHERE id = ?";
        return QueryBuilder::fetch($query, [$id]);
    }
}
```

#### **2. Data Transformation**
```php
// Dalam Aktor.php
class Aktor {
    public function getFilms() {
        // Manipulasi data: Join table dan transformasi hasil
        $query = "SELECT f.* FROM films f 
                 JOIN film_actor fa ON f.id = fa.film_id 
                 WHERE fa.actor_id = ?";
        return QueryBuilder::fetchAll($query, [$this->id]);
    }
}
```

#### **3. Complex Queries**
```php
// Dalam Jadwal.php
class Jadwal {
    public static function getJadwalHariIni($bioskopId) {
        // Manipulasi data: Query kompleks dengan multiple conditions
        $query = "SELECT j.*, f.title, f.duration, b.name as bioskop_name 
                 FROM jadwal j 
                 JOIN films f ON j.film_id = f.id 
                 JOIN bioskop b ON j.bioskop_id = b.id 
                 WHERE j.bioskop_id = ? AND j.tanggal = CURDATE() 
                 ORDER BY j.waktu_mulai";
        return QueryBuilder::fetchAll($query, [$bioskopId]);
    }
}
```

## 🎭 **PERBEDAAN UTAMA**

### **Logika Bisnis:**
- **Apa yang boleh/tidak boleh** dilakukan oleh sistem
- **Aturan domain** spesifik (bioskop, perbankan, e-commerce, dll)
- **Berubah berdasarkan kebutuhan bisnis**
- Contoh: "Film durasi pendek dapat diskon 10%"

### **Manipulasi Data:**
- **Bagaimana cara** menyimpan/mengambil data
- **Operasi teknis** database (CREATE, READ, UPDATE, DELETE)
- **Berubah berdasarkan teknologi** (MySQL, PostgreSQL, MongoDB)
- Contoh: "Cara menyimpan data film ke tabel 'films'"

## 🔄 **IMPLEMENTASI DALAM MODEL**

```php
class Film extends Model {
    // === MANIPULASI DATA ===
    public function saveToDatabase() {
        // Technical: How to save
        return QueryBuilder::insert('films', $this->toArray());
    }
    
    // === LOGIKA BISNIS ===  
    public function canBeScheduled() {
        // Business: Film harus sudah approved dan durasi valid
        return $this->status == 'approved' && 
               $this->duration >= 60 &&
               $this->hasValidRights();
    }
    
    public function calculateRevenue() {
        // Business: Revenue = ticket_price * estimated_audience
        return $this->ticket_price * $this->estimateAudience();
    }
}
```

## 💡 **KENAPA PENTING DIPISAH?**

1. **Maintainability** - Aturan bisnis berubah lebih sering daripada teknik database
2. **Testability** - Bisa test logika bisnis tanpa database
3. **Flexibility** - Ganti database technology tanpa mengubah aturan bisnis
4. **Clarity** - Kode lebih mudah dipahami dan dikelola

**Intinya**: Model adalah **"otak"** aplikasi yang mengandung baik aturan bisnis maupun kemampuan teknis mengelola data! 🧠

