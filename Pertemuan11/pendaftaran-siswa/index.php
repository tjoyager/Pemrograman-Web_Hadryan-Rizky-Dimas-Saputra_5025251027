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
                        <a class="nav-link active" href="index.php">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="form-daftar.php">Daftar Baru</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="list-siswa.php">Pendaftar</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Konten Utama -->
    <div class="container mt-4">

        <!-- Notifikasi Status Pendaftaran -->
        <?php if (isset($_GET['status'])): ?>
            <?php if ($_GET['status'] == 'sukses'): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Sukses!</strong> Pendaftaran siswa baru berhasil!
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php else: ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Gagal!</strong> Pendaftaran siswa baru gagal! Silakan coba lagi.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <!-- Hero / Landing Card Sambutan -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body p-5 text-center">
                <h6 class="text-uppercase text-muted fw-semibold mb-2">Sistem Penerimaan Peserta Didik Baru</h6>
                <h1 class="display-5 fw-bold text-primary mb-3">Selamat Datang di SMK Coding</h1>
                <p class="lead text-secondary mb-4 col-lg-8 mx-auto">
                    Portal resmi pendaftaran calon siswa baru SMK Coding. Daftarkan diri Anda sekarang untuk bergabung bersama generasi unggul di bidang teknologi dan informatika.
                </p>
                <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
                    <a href="form-daftar.php" class="btn btn-primary btn-lg px-4 gap-3 shadow-sm">Daftar Baru</a>
                    <a href="list-siswa.php" class="btn btn-outline-primary btn-lg px-4">Lihat Pendaftar</a>
                </div>
            </div>
        </div>

    </div>

    <!-- Bootstrap 5 JS Bundle via CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
