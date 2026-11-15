<?php
// Memasukkan file konfigurasi dan koneksi database
include("config.php");

// Memeriksa apakah tombol submit 'simpan' sudah diklik
if (isset($_POST['simpan'])) {

    // Mengambil data input dari formulir edit
    $id = $_POST['id'];
    $nama = trim($_POST['nama']);
    $alamat = trim($_POST['alamat']);
    $jk = isset($_POST['jenis_kelamin']) ? $_POST['jenis_kelamin'] : '';
    $agama = isset($_POST['agama']) ? $_POST['agama'] : '';
    $sekolah = trim($_POST['sekolah_asal']);

    // Logika Pengunggahan Pas Foto Baru (Fitur 2.0)
    $foto_baru = null;
    $ada_foto_baru = false;

    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['foto']['tmp_name'];
        $file_name = $_FILES['foto']['name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed_ext = ['jpg', 'jpeg', 'png'];

        // Validasi ekstensi file
        if (in_array($file_ext, $allowed_ext)) {
            $upload_dir = 'uploads/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }

            // Ambil nama file foto lama dari database untuk dihapus dari server
            $stmt_old = mysqli_prepare($db, "SELECT foto FROM calon_siswa WHERE id = ?");
            mysqli_stmt_bind_param($stmt_old, "i", $id);
            mysqli_stmt_execute($stmt_old);
            $res_old = mysqli_stmt_get_result($stmt_old);
            $data_old = mysqli_fetch_assoc($res_old);
            mysqli_stmt_close($stmt_old);

            if ($data_old && !empty($data_old['foto']) && file_exists($upload_dir . $data_old['foto'])) {
                unlink($upload_dir . $data_old['foto']);
            }

            // Simpan file foto baru
            $foto_baru = time() . '_' . uniqid() . '.' . $file_ext;
            if (move_uploaded_file($file_tmp, $upload_dir . $foto_baru)) {
                $ada_foto_baru = true;
            }
        }
    }

    // Menyiapkan query UPDATE dengan Prepared Statement
    if ($ada_foto_baru) {
        // Query UPDATE menyertakan kolom foto jika foto baru diunggah
        $sql = "UPDATE calon_siswa SET nama = ?, alamat = ?, jenis_kelamin = ?, agama = ?, sekolah_asal = ?, foto = ? WHERE id = ?";
        $stmt = mysqli_prepare($db, $sql);
        mysqli_stmt_bind_param($stmt, "ssssssi", $nama, $alamat, $jk, $agama, $sekolah, $foto_baru, $id);
    } else {
        // Query UPDATE tanpa mengubah foto jika pengguna tidak mengunggah foto baru
        $sql = "UPDATE calon_siswa SET nama = ?, alamat = ?, jenis_kelamin = ?, agama = ?, sekolah_asal = ? WHERE id = ?";
        $stmt = mysqli_prepare($db, $sql);
        mysqli_stmt_bind_param($stmt, "sssssi", $nama, $alamat, $jk, $agama, $sekolah, $id);
    }

    // Menjalankan query update
    $is_success = mysqli_stmt_execute($stmt);

    // Menutup prepared statement
    mysqli_stmt_close($stmt);

    // Mengalihkan ke list-siswa.php dengan status keberhasilan
    if ($is_success) {
        header('Location: list-siswa.php?status=sukses_edit');
        exit();
    } else {
        header('Location: list-siswa.php?status=gagal_edit');
        exit();
    }

} else {
    // Menolak akses jika file diakses langsung tanpa melalui formulir
    die("Akses dilarang...");
}
?>
