<?php
// Memasukkan file konfigurasi dan koneksi database
include("config.php");

// Mengambil seluruh data calon siswa dari database
$sql = "SELECT * FROM calon_siswa ORDER BY id ASC";
$query = mysqli_query($db, $sql);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Data Pendaftar Siswa Baru | SMK Coding</title>
    <!-- Bootstrap 5 CSS via CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        /* Pengaturan gaya cetak dokumen */
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background-color: #fff !important;
                color: #000 !important;
            }
            .table th, .table td {
                border-color: #000 !important;
            }
        }
        body {
            background-color: #fff;
            font-family: Arial, sans-serif;
        }
        .kop-surat {
            border-bottom: 3px double #000;
            margin-bottom: 20px;
            padding-bottom: 10px;
        }
    </style>
</head>
<body class="p-4">

    <!-- Tombol Cetak Manual (Hanya tampil di layar monitor) -->
    <div class="no-print mb-4 d-flex justify-content-between align-items-center">
        <a href="list-siswa.php" class="btn btn-outline-secondary btn-sm">&larr; Kembali ke Daftar Siswa</a>
        <button onclick="window.print()" class="btn btn-primary btn-sm">Cetak Ulang Dokumen</button>
    </div>

    <!-- Kop Dokumen Laporan -->
    <div class="text-center kop-surat">
        <h3 class="fw-bold mb-1">LAPORAN DATA PENDAFTARAN SISWA BARU</h3>
        <h4 class="fw-semibold mb-1">SMK CODING</h4>
        <p class="text-muted mb-0 small">Tahun Ajaran <?php echo date('Y') . '/' . (date('Y') + 1); ?> | Dicetak pada: <?php echo date('d-m-Y H:i'); ?></p>
    </div>

    <!-- Tabel Data Pendaftar (Tanpa Kolom Tindakan) -->
    <table class="table table-bordered table-striped align-middle">
        <thead class="table-light text-center">
            <tr>
                <th style="width: 40px;">No</th>
                <th style="width: 60px;">Foto</th>
                <th>Nama Lengkap</th>
                <th>Alamat</th>
                <th>Jenis Kelamin</th>
                <th>Agama</th>
                <th>Sekolah Asal</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            $total_siswa = mysqli_num_rows($query);

            if ($total_siswa > 0) {
                while ($siswa = mysqli_fetch_array($query)) {
                    echo "<tr>";
                    echo "<td class='text-center'>" . $no++ . "</td>";
                    
                    // Thumbnail Foto
                    echo "<td class='text-center'>";
                    if (!empty($siswa['foto']) && file_exists('uploads/' . $siswa['foto'])) {
                        echo "<img src='uploads/" . htmlspecialchars($siswa['foto']) . "' width='50' height='50' style='object-fit: cover;' class='border rounded' alt='Foto'>";
                    } else {
                        echo "<span class='text-muted small'>-</span>";
                    }
                    echo "</td>";

                    echo "<td>" . htmlspecialchars($siswa['nama']) . "</td>";
                    echo "<td>" . htmlspecialchars($siswa['alamat']) . "</td>";
                    echo "<td>" . htmlspecialchars(ucfirst($siswa['jenis_kelamin'])) . "</td>";
                    echo "<td>" . htmlspecialchars($siswa['agama']) . "</td>";
                    echo "<td>" . htmlspecialchars($siswa['sekolah_asal']) . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7' class='text-center py-3 text-muted'>Belum ada data calon siswa.</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <!-- Keterangan Total Siswa -->
    <div class="d-flex justify-content-between mt-3">
        <p class="fw-semibold">Total Calon Siswa: <?php echo $total_siswa; ?> orang</p>
        <div class="text-center" style="width: 200px;">
            <p class="mb-5">Panitia PPDB,</p>
            <p class="fw-bold mb-0">SMK Coding</p>
        </div>
    </div>

    <!-- JavaScript Otomatis Membuka Jendela Cetak Saat Halaman Dibuka -->
    <script>
        window.addEventListener('DOMContentLoaded', () => {
            window.print();
        });
    </script>
</body>
</html>
