

TABEL UNTUK P!X

```sql

-- Tabel aktors
CREATE TABLE `aktors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `birth_date` date DEFAULT NULL,
  `nationality` varchar(50) DEFAULT NULL,
  `photo_url` varchar(500) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tabel bioskops
CREATE TABLE `bioskops` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `location` varchar(150) NOT NULL,
  `total_studio` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tabel jadwal_films
CREATE TABLE `jadwal_films` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `movie_id` int(11) NOT NULL,
  `bioskop_id` int(11) NOT NULL,
  `show_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_movie` (`movie_id`),
  KEY `fk_bioskop` (`bioskop_id`),
  CONSTRAINT `fk_movie` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_bioskop` FOREIGN KEY (`bioskop_id`) REFERENCES `bioskops` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

```


| No  | Relasi                               | Jenis Relasi             | Penjelasan                                                                                                                                                        |
| --- | ------------------------------------ | ------------------------ | ----------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| 1️⃣ | `genres` → `movies`                  | **One to Many (1 : N)**  | Satu genre bisa memiliki banyak film, tapi setiap film hanya punya satu genre. <br>Relasi lewat kolom `movies.genre_id` → `genres.id`                             |
| 2️⃣ | `movies` → `jadwal_films`            | **One to Many (1 : N)**  | Satu film bisa punya banyak jadwal tayang di berbagai bioskop. <br>Relasi lewat kolom `jadwal_films.movie_id` → `movies.id`                                       |
| 3️⃣ | `bioskops` → `jadwal_films`          | **One to Many (1 : N)**  | Satu bioskop bisa menayangkan banyak film (jadwal berbeda). <br>Relasi lewat kolom `jadwal_films.bioskop_id` → `bioskops.id`                                      |
| 4️⃣ | `aktors` ↔ `movies` *(opsional)*     | **Many to Many (M : N)** | Satu aktor bisa bermain di banyak film, dan satu film bisa punya banyak aktor. <br>Biasanya dibuat lewat tabel penghubung `movie_aktors` (`movie_id`, `aktor_id`) |
| 5️⃣ | `movies` ↔ `genres` ↔ `jadwal_films` | **Relasi beran**_        |                                                                                                                                                                   |
