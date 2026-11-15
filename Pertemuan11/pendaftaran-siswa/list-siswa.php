<?php
// Memasukkan file konfigurasi dan koneksi database
include("config.php");

// Menangkap parameter pencarian (Fitur 2.0)
$is_search = isset($_GET['cari']) && trim($_GET['cari']) !== '';
$cari = $is_search ? trim($_GET['cari']) : '';

// Mengambil data siswa dengan Prepared Statement jika ada pencarian
if ($is_search) {
    $param_cari = "%" . $cari . "%";
    $sql = "SELECT * FROM calon_siswa WHERE nama LIKE ? OR sekolah_asal LIKE ? ORDER BY id DESC";
    $stmt = mysqli_prepare($db, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $param_cari, $param_cari);
    mysqli_stmt_execute($stmt);
    $query = mysqli_stmt_get_result($stmt);
} else {
    $sql = "SELECT * FROM calon_siswa ORDER BY id DESC";
    $query = mysqli_query($db, $sql);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Siswa Baru | SMK Coding</title>
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

        <!-- Notifikasi Status Aksi -->
        <?php if (isset($_GET['status'])): ?>
            <?php if ($_GET['status'] == 'sukses_edit'): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Sukses!</strong> Data siswa berhasil diperbarui.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php elseif ($_GET['status'] == 'sukses_hapus'): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Sukses!</strong> Data siswa berhasil dihapus.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php elseif ($_GET['status'] == 'gagal_edit'): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Gagal!</strong> Terjadi kesalahan saat memperbarui data siswa.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php elseif ($_GET['status'] == 'gagal_hapus'): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Gagal!</strong> Terjadi kesalahan saat menghapus data siswa.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <!-- Card Daftar Siswa -->
        <div class="card shadow-sm border-0">
            <!-- Header Card dengan Tombol Tambah dan Cetak Laporan -->
            <div class="card-header bg-white py-3 d-flex flex-wrap justify-content-between align-items-center gap-2">
                <h4 class="card-title mb-0 fw-semibold text-primary">Daftar Siswa yang Sudah Mendaftar</h4>
                <div class="d-flex gap-2">
                    <a href="cetak.php" target="_blank" class="btn btn-secondary btn-sm px-3">Cetak Laporan</a>
                    <a href="form-daftar.php" class="btn btn-primary btn-sm px-3">[+] Tambah Baru</a>
                </div>
            </div>

            <!-- Form Pencarian (Fitur 2.0: Input Group Bootstrap) -->
            <div class="card-body border-bottom bg-light py-3">
                <form action="list-siswa.php" method="GET">
                    <div class="input-group">
                        <input type="text" name="cari" class="form-control" placeholder="Cari berdasarkan nama atau sekolah asal..." value="<?php echo htmlspecialchars($cari); ?>">
                        <button class="btn btn-primary" type="submit">Cari</button>
                        <?php if ($is_search): ?>
                            <a href="list-siswa.php" class="btn btn-outline-secondary">Reset</a>
                        <?php endif; ?>
                    </div>
                </form>
                <?php if ($is_search): ?>
                    <small class="text-muted mt-2 d-block">
                        Hasil pencarian untuk kata kunci: "<strong><?php echo htmlspecialchars($cari); ?></strong>"
                    </small>
                <?php endif; ?>
            </div>

            <!-- Tabel Data Siswa -->
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-bordered mb-0 align-middle">
                        <thead class="table-primary text-nowrap">
                            <tr>
                                <th scope="col" class="text-center" style="width: 50px;">No</th>
                                <th scope="col" class="text-center" style="width: 70px;">Foto</th>
                                <th scope="col">Nama</th>
                                <th scope="col">Alamat</th>
                                <th scope="col">Jenis Kelamin</th>
                                <th scope="col">Agama</th>
                                <th scope="col">Sekolah Asal</th>
                                <th scope="col" class="text-center" style="width: 140px;">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $total_data = mysqli_num_rows($query);
                            $no = 1;

                            if ($total_data > 0) {
                                while ($siswa = mysqli_fetch_array($query)) {
                                    echo "<tr>";
                                    echo "<td class='text-center'>" . $no++ . "</td>";
                                    
                                    // Kolom Thumbnail Pas Foto (Fitur 2.0)
                                    echo "<td class='text-center'>";
                                    if (!empty($siswa['foto']) && file_exists('uploads/' . $siswa['foto'])) {
                                        echo "<img src='uploads/" . htmlspecialchars($siswa['foto']) . "' width='50' class='rounded border shadow-sm' alt='Foto Siswa'>";
                                    } else {
                                        echo "<span class='badge bg-light text-secondary border'>Tidak ada</span>";
                                    }
                                    echo "</td>";

                                    echo "<td>" . htmlspecialchars($siswa['nama']) . "</td>";
                                    echo "<td>" . htmlspecialchars($siswa['alamat']) . "</td>";
                                    echo "<td>" . htmlspecialchars(ucfirst($siswa['jenis_kelamin'])) . "</td>";
                                    echo "<td>" . htmlspecialchars($siswa['agama']) . "</td>";
                                    echo "<td>" . htmlspecialchars($siswa['sekolah_asal']) . "</td>";
                                    echo "<td class='text-center text-nowrap'>";
                                    echo "<a href='form-edit.php?id=" . $siswa['id'] . "' class='btn btn-sm btn-warning me-1'>Edit</a>";
                                    echo "<a href='hapus.php?id=" . $siswa['id'] . "' class='btn btn-sm btn-danger' onclick=\"return confirm('Yakin ingin menghapus?');\">Hapus</a>";
                                    echo "</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='8' class='text-center py-4 text-muted'>Tidak ada data siswa yang ditemukan.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Footer Card: Total Data Siswa -->
            <div class="card-footer bg-white text-muted py-3">
                <span>Total: <strong><?php echo $total_data; ?></strong> siswa</span>
            </div>
        </div>

    </div>

    <!-- Bootstrap 5 JS Bundle via CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
