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

Perubahan yang perlu dilakukan 
- Dashboard.php perlu diperbaiki fungsi pada website tidak berjalan 
- 