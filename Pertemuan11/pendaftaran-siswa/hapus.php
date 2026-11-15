<?php
// Memasukkan file konfigurasi dan koneksi database
include("config.php");

// Memeriksa apakah parameter id dikirimkan melalui URL
if (isset($_GET['id'])) {

    // Mengambil id siswa dari query string
    $id = $_GET['id'];

    // Ambil nama file foto sebelum data siswa dihapus (Fitur 2.0)
    $stmt_foto = mysqli_prepare($db, "SELECT foto FROM calon_siswa WHERE id = ?");
    mysqli_stmt_bind_param($stmt_foto, "i", $id);
    mysqli_stmt_execute($stmt_foto);
    $res_foto = mysqli_stmt_get_result($stmt_foto);
    $data_foto = mysqli_fetch_assoc($res_foto);
    mysqli_stmt_close($stmt_foto);

    // Hapus file foto dari server jika ada
    if ($data_foto && !empty($data_foto['foto']) && file_exists('uploads/' . $data_foto['foto'])) {
        unlink('uploads/' . $data_foto['foto']);
    }

    // Menyiapkan query DELETE dengan Prepared Statement untuk mencegah SQL Injection
    $sql = "DELETE FROM calon_siswa WHERE id = ?";
    $stmt = mysqli_prepare($db, $sql);

    // Mengikat parameter ID bertipe integer ('i')
    mysqli_stmt_bind_param($stmt, "i", $id);

    // Menjalankan query hapus yang sudah disiapkan
    $is_success = mysqli_stmt_execute($stmt);

    // Menutup prepared statement
    mysqli_stmt_close($stmt);

    // Mengalihkan ke list-siswa.php dengan status keberhasilan
    if ($is_success) {
        header('Location: list-siswa.php?status=sukses_hapus');
        exit();
    } else {
        header('Location: list-siswa.php?status=gagal_hapus');
        exit();
    }

} else {
    // Menolak akses jika parameter id tidak tersedia
    die("Akses dilarang...");
}
?>
