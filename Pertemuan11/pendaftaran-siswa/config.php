<?php
/**
 * Konfigurasi koneksi database MySQL
 * 
 * QUERY ALTER TABLE UNTUK FITUR UPLOAD FOTO (VERSI 2.0):
 * ALTER TABLE calon_siswa ADD COLUMN foto VARCHAR(255) NULL AFTER sekolah_asal;
 */

$server = "localhost";
$user = "admin";
$password = "SandiKuat123";
$nama_database = "pendaftaran_siswa";

// Membuka koneksi ke server database
$db = mysqli_connect($server, $user, $password, $nama_database);

// Memeriksa status koneksi
if (!$db) {
    die("Gagal terhubung dengan database: " . mysqli_connect_error());
}

// Cek otomatis dan tambahkan kolom 'foto' jika belum ada pada tabel calon_siswa
$cek_kolom = mysqli_query($db, "SHOW COLUMNS FROM calon_siswa LIKE 'foto'");
if ($cek_kolom && mysqli_num_rows($cek_kolom) === 0) {
    mysqli_query($db, "ALTER TABLE calon_siswa ADD COLUMN foto VARCHAR(255) NULL AFTER sekolah_asal");
}
?>
