<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulir Pendaftaran Siswa Baru | SMK Coding</title>
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
                        <a class="nav-link active" href="form-daftar.php">Daftar Baru</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="list-siswa.php">Pendaftar</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Konten Utama -->
    <div class="container mt-4 mb-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Card Formulir Pendaftaran -->
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white py-3">
                        <h4 class="card-title mb-0 fw-semibold">Formulir Pendaftaran Siswa Baru</h4>
                    </div>
                    <div class="card-body p-4">
                        <form action="proses-pendaftaran.php" method="POST" enctype="multipart/form-data">
                            
                            <!-- Input Nama Lengkap -->
                            <div class="mb-3">
                                <label for="nama" class="form-label fw-medium">Nama Lengkap</label>
                                <input type="text" class="form-control" id="nama" name="nama" placeholder="Masukkan nama lengkap" required>
                            </div>

                            <!-- Input Alamat -->
                            <div class="mb-3">
                                <label for="alamat" class="form-label fw-medium">Alamat</label>
                                <textarea class="form-control" id="alamat" name="alamat" rows="3" placeholder="Masukkan alamat lengkap" required></textarea>
                            </div>

                            <!-- Input Jenis Kelamin -->
                            <div class="mb-3">
                                <label class="form-label fw-medium d-block">Jenis Kelamin</label>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="jenis_kelamin" id="jk_l" value="laki-laki" required>
                                    <label class="form-check-label" for="jk_l">Laki-laki</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="jenis_kelamin" id="jk_p" value="perempuan">
                                    <label class="form-check-label" for="jk_p">Perempuan</label>
                                </div>
                            </div>

                            <!-- Input Agama -->
                            <div class="mb-3">
                                <label for="agama" class="form-label fw-medium">Agama</label>
                                <select class="form-select" id="agama" name="agama" required>
                                    <option value="" disabled selected>-- Pilih Agama --</option>
                                    <option value="Islam">Islam</option>
                                    <option value="Kristen">Kristen</option>
                                    <option value="Hindu">Hindu</option>
                                    <option value="Budha">Budha</option>
                                    <option value="Atheis">Atheis</option>
                                </select>
                            </div>

                            <!-- Input Sekolah Asal -->
                            <div class="mb-3">
                                <label for="sekolah_asal" class="form-label fw-medium">Sekolah Asal</label>
                                <input type="text" class="form-control" id="sekolah_asal" name="sekolah_asal" placeholder="Nama sekolah asal (contoh: SMPN 1 Surabaya)" required>
                            </div>

                            <!-- Input Pas Foto (Fitur 2.0) -->
                            <div class="mb-4">
                                <label for="foto" class="form-label fw-medium">Pas Foto</label>
                                <input class="form-control" type="file" id="foto" name="foto" accept="image/jpeg,image/png,image/jpg">
                                <div class="form-text">Pilih file gambar berformat JPG, JPEG, atau PNG.</div>
                            </div>

                            <!-- Tombol Aksi -->
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="index.php" class="btn btn-outline-secondary">Batal</a>
                                <button type="submit" name="daftar" class="btn btn-primary px-4">Daftar Sekarang</button>
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
