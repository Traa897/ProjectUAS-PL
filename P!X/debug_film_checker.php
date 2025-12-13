<?php
// File: debug_film_checker.php
// Letakkan di root folder P!X dan akses via browser: http://localhost/PL/Teori/Week9/P!X/debug_film_checker.php

require_once 'config/database.php';

$database = new Database();
$db = $database->getConnection();

echo "<h1>🔍 Debug Film & Jadwal</h1>";
echo "<style>
    body { font-family: monospace; padding: 20px; background: #f5f5f5; }
    table { border-collapse: collapse; width: 100%; margin: 20px 0; background: white; }
    th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
    th { background: #032541; color: white; }
    .has-schedule { background: #d4edda; }
    .no-schedule { background: #f8d7da; }
    h2 { color: #032541; margin-top: 30px; }
    .info { background: #fff3cd; padding: 15px; border-radius: 5px; margin: 20px 0; }
</style>";

// ✅ QUERY 1: CEK SEMUA FILM DI DATABASE
echo "<h2>📊 SEMUA FILM DI DATABASE</h2>";
$query1 = "SELECT f.id_film, f.judul_film, f.tahun_rilis, 
           (SELECT COUNT(*) FROM Jadwal_Tayang WHERE id_film = f.id_film) as jumlah_jadwal
           FROM Film f
           ORDER BY f.id_film ASC";
$stmt1 = $db->prepare($query1);
$stmt1->execute();
$allFilms = $stmt1->fetchAll(PDO::FETCH_ASSOC);

echo "<table>";
echo "<tr><th>ID</th><th>Judul Film</th><th>Tahun</th><th>Jumlah Jadwal</th><th>Status</th></tr>";
foreach($allFilms as $film) {
    $class = $film['jumlah_jadwal'] > 0 ? 'has-schedule' : 'no-schedule';
    $status = $film['jumlah_jadwal'] > 0 ? '✅ Punya Jadwal' : '❌ Belum Ada Jadwal';
    echo "<tr class='$class'>";
    echo "<td>{$film['id_film']}</td>";
    echo "<td>{$film['judul_film']}</td>";
    echo "<td>{$film['tahun_rilis']}</td>";
    echo "<td>{$film['jumlah_jadwal']}</td>";
    echo "<td><strong>$status</strong></td>";
    echo "</tr>";
}
echo "</table>";

$totalFilms = count($allFilms);
$filmsWithSchedule = count(array_filter($allFilms, fn($f) => $f['jumlah_jadwal'] > 0));
$filmsWithoutSchedule = $totalFilms - $filmsWithSchedule;

echo "<div class='info'>";
echo "<strong>📈 Ringkasan:</strong><br>";
echo "Total Film: <strong>$totalFilms</strong><br>";
echo "Film dengan Jadwal: <strong>$filmsWithSchedule</strong><br>";
echo "Film tanpa Jadwal: <strong>$filmsWithoutSchedule</strong>";
echo "</div>";

// ✅ QUERY 2: CEK QUERY readAll() YANG DIGUNAKAN HALAMAN PUBLIC
echo "<h2>🌐 QUERY readAll() - UNTUK HALAMAN PUBLIC/FILM</h2>";
echo "<p>Query ini digunakan di <code>models/Film.php</code> method <code>readAll()</code></p>";

$query2 = "SELECT 
            f.id_film, 
            f.judul_film, 
            f.tahun_rilis, 
            f.durasi_menit, 
            f.sipnosis, 
            f.rating, 
            f.poster_url, 
            f.id_genre, 
            g.nama_genre
          FROM Film f
          LEFT JOIN Genre g ON f.id_genre = g.id_genre
          WHERE EXISTS (
              SELECT 1 FROM Jadwal_Tayang jt 
              WHERE jt.id_film = f.id_film
          )
          GROUP BY f.id_film
          ORDER BY f.tahun_rilis DESC, f.id_film ASC";

$stmt2 = $db->prepare($query2);
$stmt2->execute();
$publicFilms = $stmt2->fetchAll(PDO::FETCH_ASSOC);

echo "<table>";
echo "<tr><th>ID</th><th>Judul Film</th><th>Genre</th><th>Rating</th></tr>";
foreach($publicFilms as $film) {
    echo "<tr class='has-schedule'>";
    echo "<td>{$film['id_film']}</td>";
    echo "<td>{$film['judul_film']}</td>";
    echo "<td>{$film['nama_genre']}</td>";
    echo "<td>{$film['rating']}</td>";
    echo "</tr>";
}
echo "</table>";

echo "<div class='info'>";
echo "<strong>✅ Film yang TAMPIL di Halaman Public: " . count($publicFilms) . " film</strong><br>";
echo "Film yang TIDAK tampil: " . ($totalFilms - count($publicFilms)) . " film (karena belum ada jadwal)";
echo "</div>";

// ✅ QUERY 3: CEK QUERY readAllIncludingNoSchedule() UNTUK ADMIN
echo "<h2>👨‍💼 QUERY readAllIncludingNoSchedule() - UNTUK ADMIN DASHBOARD</h2>";
echo "<p>Query ini digunakan di <code>controllers/AdminController.php</code> method <code>dashboard()</code></p>";

$query3 = "SELECT 
            f.id_film, 
            f.judul_film, 
            f.tahun_rilis, 
            f.durasi_menit, 
            f.sipnosis, 
            f.rating, 
            f.poster_url, 
            f.id_genre, 
            g.nama_genre
          FROM Film f
          LEFT JOIN Genre g ON f.id_genre = g.id_genre
          ORDER BY f.created_at DESC, f.id_film DESC";

$stmt3 = $db->prepare($query3);
$stmt3->execute();
$adminFilms = $stmt3->fetchAll(PDO::FETCH_ASSOC);

echo "<table>";
echo "<tr><th>ID</th><th>Judul Film</th><th>Genre</th><th>Punya Jadwal?</th></tr>";
foreach($adminFilms as $film) {
    $hasSchedule = false;
    foreach($allFilms as $f) {
        if($f['id_film'] == $film['id_film']) {
            $hasSchedule = $f['jumlah_jadwal'] > 0;
            break;
        }
    }
    $class = $hasSchedule ? 'has-schedule' : 'no-schedule';
    $status = $hasSchedule ? '✅ Ada' : '❌ Belum';
    echo "<tr class='$class'>";
    echo "<td>{$film['id_film']}</td>";
    echo "<td>{$film['judul_film']}</td>";
    echo "<td>{$film['nama_genre']}</td>";
    echo "<td><strong>$status</strong></td>";
    echo "</tr>";
}
echo "</table>";

echo "<div class='info'>";
echo "<strong>✅ Film yang TAMPIL di Admin Dashboard: " . count($adminFilms) . " film</strong><br>";
echo "Semua film harus tampil di dashboard admin (termasuk yang belum ada jadwal)";
echo "</div>";

// ✅ QUERY 4: CEK JADWAL TAYANG
echo "<h2>📅 JADWAL TAYANG</h2>";
$query4 = "SELECT jt.id_tayang, f.judul_film, jt.tanggal_tayang, jt.jam_mulai, b.nama_bioskop
           FROM Jadwal_Tayang jt
           JOIN Film f ON jt.id_film = f.id_film
           JOIN Bioskop b ON jt.id_bioskop = b.id_bioskop
           ORDER BY jt.tanggal_tayang DESC, f.judul_film ASC";
$stmt4 = $db->prepare($query4);
$stmt4->execute();
$schedules = $stmt4->fetchAll(PDO::FETCH_ASSOC);

echo "<table>";
echo "<tr><th>Film</th><th>Tanggal</th><th>Jam</th><th>Bioskop</th></tr>";
foreach($schedules as $schedule) {
    echo "<tr>";
    echo "<td>{$schedule['judul_film']}</td>";
    echo "<td>{$schedule['tanggal_tayang']}</td>";
    echo "<td>{$schedule['jam_mulai']}</td>";
    echo "<td>{$schedule['nama_bioskop']}</td>";
    echo "</tr>";
}
echo "</table>";

echo "<div class='info'>";
echo "<strong>📊 Total Jadwal Tayang: " . count($schedules) . " jadwal</strong>";
echo "</div>";

// ✅ KESIMPULAN
echo "<h2>🎯 KESIMPULAN & DIAGNOSIS</h2>";
echo "<div class='info'>";
echo "<ol>";
echo "<li><strong>Total Film di Database:</strong> $totalFilms film</li>";
echo "<li><strong>Film dengan Jadwal:</strong> $filmsWithSchedule film → Harus tampil di halaman PUBLIC</li>";
echo "<li><strong>Film tanpa Jadwal:</strong> $filmsWithoutSchedule film → TIDAK tampil di halaman PUBLIC, tapi HARUS tampil di ADMIN</li>";
echo "<li><strong>Query readAll() menghasilkan:</strong> " . count($publicFilms) . " film</li>";
echo "<li><strong>Query readAllIncludingNoSchedule() menghasilkan:</strong> " . count($adminFilms) . " film</li>";
echo "</ol>";

if(count($publicFilms) == $filmsWithSchedule) {
    echo "<p style='color: green; font-weight: bold;'>✅ QUERY PUBLIC BENAR: Hanya menampilkan film dengan jadwal</p>";
} else {
    echo "<p style='color: red; font-weight: bold;'>❌ QUERY PUBLIC SALAH: Harusnya tampil $filmsWithSchedule film, tapi hanya tampil " . count($publicFilms) . " film</p>";
}

if(count($adminFilms) == $totalFilms) {
    echo "<p style='color: green; font-weight: bold;'>✅ QUERY ADMIN BENAR: Menampilkan SEMUA film</p>";
} else {
    echo "<p style='color: red; font-weight: bold;'>❌ QUERY ADMIN SALAH: Harusnya tampil $totalFilms film, tapi hanya tampil " . count($adminFilms) . " film</p>";
}

echo "</div>";

echo "<h2>🔧 LANGKAH PERBAIKAN</h2>";
echo "<div class='info'>";
echo "<p>Jika ada masalah, copy query yang bermasalah dan jalankan langsung di phpMyAdmin untuk cek hasilnya.</p>";
echo "<p><strong>File yang perlu dicek:</strong></p>";
echo "<ul>";
echo "<li><code>models/Film.php</code> → method readAll() dan readAllIncludingNoSchedule()</li>";
echo "<li><code>controllers/FilmController.php</code> → method index()</li>";
echo "<li><code>controllers/AdminController.php</code> → method dashboard()</li>";
echo "</ul>";
echo "</div>";
?>