<?php
// Memasukkan file konfigurasi dan koneksi database
include("config.php");

// Cek apakah parameter id tersedia di URL dan berupa angka
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: list-siswa.php');
    exit();
}

// Ambil ID siswa dari query string
$id = $_GET['id'];

// Ambil data siswa berdasarkan ID menggunakan Prepared Statement untuk mencegah SQL Injection
$sql = "SELECT * FROM calon_siswa WHERE id = ?";
$stmt = mysqli_prepare($db, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$siswa = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

// Jika data yang ingin diedit tidak ditemukan di database
if (!$siswa) {
    die("Data siswa tidak ditemukan...");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulir Edit Siswa | SMK Coding</title>
    <!-- Bootstrap 5 CSS via CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body class="bg-light">

    <!-- Navbar Navigasi Utama -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">SMK Coding</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="form-daftar.php">Daftar Baru</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="list-siswa.php">Pendaftar</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Konten Utama -->
    <div class="container mt-4 mb-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Card Formulir Edit Siswa -->
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-warning text-dark py-3">
                        <h4 class="card-title mb-0 fw-semibold">Formulir Edit Data Siswa</h4>
                    </div>
                    <div class="card-body p-4">
                        <form action="proses-edit.php" method="POST" enctype="multipart/form-data">
                            
                            <!-- Input Hidden ID Siswa -->
                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($siswa['id']); ?>" />

                            <!-- Input Nama Lengkap -->
                            <div class="mb-3">
                                <label for="nama" class="form-label fw-medium">Nama Lengkap</label>
                                <input type="text" class="form-control" id="nama" name="nama" placeholder="Masukkan nama lengkap" value="<?php echo htmlspecialchars($siswa['nama']); ?>" required>
                            </div>

                            <!-- Input Alamat -->
                            <div class="mb-3">
                                <label for="alamat" class="form-label fw-medium">Alamat</label>
                                <textarea class="form-control" id="alamat" name="alamat" rows="3" placeholder="Masukkan alamat lengkap" required><?php echo htmlspecialchars($siswa['alamat']); ?></textarea>
                            </div>

                            <!-- Input Jenis Kelamin -->
                            <div class="mb-3">
                                <label class="form-label fw-medium d-block">Jenis Kelamin</label>
                                <?php $jk = $siswa['jenis_kelamin']; ?>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="jenis_kelamin" id="jk_l" value="laki-laki" <?php echo ($jk == 'laki-laki') ? "checked" : ""; ?> required>
                                    <label class="form-check-label" for="jk_l">Laki-laki</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="jenis_kelamin" id="jk_p" value="perempuan" <?php echo ($jk == 'perempuan') ? "checked" : ""; ?>>
                                    <label class="form-check-label" for="jk_p">Perempuan</label>
                                </div>
                            </div>

                            <!-- Input Agama -->
                            <div class="mb-3">
                                <label for="agama" class="form-label fw-medium">Agama</label>
                                <?php $agama = $siswa['agama']; ?>
                                <select class="form-select" id="agama" name="agama" required>
                                    <option value="Islam" <?php echo ($agama == 'Islam') ? "selected" : ""; ?>>Islam</option>
                                    <option value="Kristen" <?php echo ($agama == 'Kristen') ? "selected" : ""; ?>>Kristen</option>
                                    <option value="Hindu" <?php echo ($agama == 'Hindu') ? "selected" : ""; ?>>Hindu</option>
                                    <option value="Budha" <?php echo ($agama == 'Budha') ? "selected" : ""; ?>>Budha</option>
                                    <option value="Atheis" <?php echo ($agama == 'Atheis') ? "selected" : ""; ?>>Atheis</option>
                                </select>
                            </div>

                            <!-- Input Sekolah Asal -->
                            <div class="mb-3">
                                <label for="sekolah_asal" class="form-label fw-medium">Sekolah Asal</label>
                                <input type="text" class="form-control" id="sekolah_asal" name="sekolah_asal" placeholder="Nama sekolah asal" value="<?php echo htmlspecialchars($siswa['sekolah_asal']); ?>" required>
                            </div>

                            <!-- Input Pas Foto (Fitur 2.0) -->
                            <div class="mb-4">
                                <label for="foto" class="form-label fw-medium">Pas Foto</label>
                                <?php if (!empty($siswa['foto']) && file_exists('uploads/' . $siswa['foto'])): ?>
                                    <div class="mb-2">
                                        <img src="uploads/<?php echo htmlspecialchars($siswa['foto']); ?>" alt="Foto Siswa" width="100" class="img-thumbnail rounded d-block shadow-sm">
                                        <small class="text-muted">Foto saat ini</small>
                                    </div>
                                <?php endif; ?>
                                <input class="form-control" type="file" id="foto" name="foto" accept="image/jpeg,image/png,image/jpg">
                                <div class="form-text">Biarkan kosong jika tidak ingin mengubah foto. Format: JPG, JPEG, PNG.</div>
                            </div>

                            <!-- Tombol Aksi -->
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="list-siswa.php" class="btn btn-outline-secondary">Batal</a>
                                <button type="submit" name="simpan" class="btn btn-warning px-4 fw-medium">Simpan Perubahan</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle via CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
