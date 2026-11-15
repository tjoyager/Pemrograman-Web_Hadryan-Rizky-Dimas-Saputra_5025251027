<?php
// Memasukkan file konfigurasi dan koneksi database
include("config.php");

// Memeriksa apakah tombol submit 'daftar' sudah diklik
if (isset($_POST['daftar'])) {

    // Mengambil dan membersihkan data input formulir
    $nama = trim($_POST['nama']);
    $alamat = trim($_POST['alamat']);
    $jk = isset($_POST['jenis_kelamin']) ? $_POST['jenis_kelamin'] : '';
    $agama = isset($_POST['agama']) ? $_POST['agama'] : '';
    $sekolah = trim($_POST['sekolah_asal']);

    // Logika Pengunggahan Pas Foto (Fitur 2.0)
    $foto_nama = null;
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['foto']['tmp_name'];
        $file_name = $_FILES['foto']['name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed_ext = ['jpg', 'jpeg', 'png'];

        // Validasi ekstensi file gambar
        if (in_array($file_ext, $allowed_ext)) {
            // Pastikan direktori uploads/ tersedia
            $upload_dir = 'uploads/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }

            // Generate nama file unik agar tidak saling menimpa
            $foto_nama = time() . '_' . uniqid() . '.' . $file_ext;
            $destination = $upload_dir . $foto_nama;

            // Pindahkan file yang diunggah ke folder uploads/
            move_uploaded_file($file_tmp, $destination);
        }
    }

    // Menyiapkan query INSERT dengan Prepared Statement untuk mencegah SQL Injection
    $sql = "INSERT INTO calon_siswa (nama, alamat, jenis_kelamin, agama, sekolah_asal, foto) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($db, $sql);

    // Mengikat parameter form ke placeholder '?' (tipe data string: 'ssssss')
    mysqli_stmt_bind_param($stmt, "ssssss", $nama, $alamat, $jk, $agama, $sekolah, $foto_nama);

    // Menjalankan query yang sudah disiapkan
    $is_success = mysqli_stmt_execute($stmt);

    // Menutup prepared statement
    mysqli_stmt_close($stmt);

    // Mengalihkan ke index.php dengan parameter status sesuai hasil eksekusi
    if ($is_success) {
        header('Location: index.php?status=sukses');
        exit();
    } else {
        header('Location: index.php?status=gagal');
        exit();
    }

} else {
    // Menolak akses jika file diakses langsung tanpa melalui formulir
    die("Akses dilarang...");
}
?>
