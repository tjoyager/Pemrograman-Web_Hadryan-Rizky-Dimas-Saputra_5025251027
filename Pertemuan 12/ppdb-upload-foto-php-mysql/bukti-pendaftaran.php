<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/bootstrap.php';
require_once __DIR__ . '/includes/student_layout.php';
require_student();

$pdo = db();
$userId = (int) $_SESSION['user']['id'];
$reg = current_registration($pdo, $userId);

if (!$reg) {
    flash('error', 'Silakan pilih program keahlian terlebih dahulu untuk membuat pendaftaran.');
    redirect('pendaftaran.php');
}

// Ambil data lengkap siswa dan akun
$stmt = $pdo->prepare(
    'SELECT s.*, u.email
     FROM siswa s
     INNER JOIN users u ON u.id = s.user_id
     WHERE s.user_id = :uid
     LIMIT 1'
);
$stmt->execute(['uid' => $userId]);
$siswa = $stmt->fetch() ?: [];

// Ambil dokumen pas foto jika ada
$fotoStmt = $pdo->prepare(
    'SELECT id, nama_file_asli, mime_type, ukuran_file, created_at
     FROM dokumen_pendaftaran
     WHERE pendaftaran_id = :pid AND jenis_dokumen = "foto"
     LIMIT 1'
);
$fotoStmt->execute(['pid' => $reg['id']]);
$fotoDoc = $fotoStmt->fetch() ?: null;

// Format tanggal lahir
$tglLahir = '-';
if (!empty($siswa['tanggal_lahir'])) {
    $time = strtotime((string)$siswa['tanggal_lahir']);
    if ($time !== false) {
        $tglLahir = date('d-m-Y', $time);
    }
}

// Format tanggal pendaftaran / kirim
$tglPendaftaran = '-';
$rawTgl = $reg['tanggal_kirim'] ?? $reg['created_at'] ?? null;
if ($rawTgl) {
    $time = strtotime((string)$rawTgl);
    if ($time !== false) {
        $tglPendaftaran = date('d-m-Y H:i', $time) . ' WIB';
    }
}

$jenisKelaminText = '-';
if (($siswa['jenis_kelamin'] ?? '') === 'L') {
    $jenisKelaminText = 'Laki-laki';
} elseif (($siswa['jenis_kelamin'] ?? '') === 'P') {
    $jenisKelaminText = 'Perempuan';
}

student_header('Bukti Pendaftaran', 'bukti');
?>
<style>
/* Tampilan Layar */
.bukti-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
    margin-bottom: 20px;
}
.btn-group {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}
.bukti-card {
    background: #ffffff;
    border: 1px solid #dce8f6;
    border-radius: 12px;
    padding: 28px 32px;
    box-shadow: 0 4px 14px rgba(18, 63, 117, 0.06);
    color: #111827;
    font-family: Arial, Helvetica, sans-serif;
    line-height: 1.5;
}
.kop-surat {
    text-align: center;
    border-bottom: 3px double #1f2937;
    padding-bottom: 12px;
    margin-bottom: 18px;
}
.kop-instansi {
    font-size: 14px;
    font-weight: 700;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    color: #1f2937;
    margin: 0 0 2px;
}
.kop-sekolah {
    font-size: 20px;
    font-weight: 800;
    letter-spacing: 1px;
    color: #0b3970;
    margin: 0 0 4px;
}
.kop-panitia {
    font-size: 13px;
    font-weight: 700;
    color: #1f2937;
    margin: 0 0 3px;
}
.kop-alamat {
    font-size: 11px;
    color: #4b5563;
    margin: 0;
}
.judul-dokumen {
    text-align: center;
    margin: 16px 0 20px;
}
.judul-dokumen h2 {
    font-size: 17px;
    font-weight: 800;
    letter-spacing: 0.5px;
    text-transform: uppercase;
    color: #111827;
    margin: 0 0 4px;
    text-decoration: underline;
}
.judul-dokumen p {
    font-size: 12px;
    color: #374151;
    margin: 0;
    font-weight: 600;
}
.nomor-badge-box {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 10px 14px;
    margin-bottom: 18px;
    font-size: 12px;
}
.nomor-badge-box b {
    color: #0b3970;
    font-size: 14px;
}
.status-tag {
    display: inline-block;
    padding: 3px 10px;
    border-radius: 14px;
    font-size: 11px;
    font-weight: 800;
    text-transform: uppercase;
}
.status-draft { background: #e2e8f0; color: #334155; }
.status-submitted { background: #dbeafe; color: #1e40af; }
.status-verified { background: #dcfce7; color: #166534; }
.status-accepted { background: #d1fae5; color: #065f46; }
.status-rejected { background: #fee2e2; color: #991b1b; }

.content-layout {
    display: grid;
    grid-template-columns: 1fr 140px;
    gap: 20px;
    align-items: start;
    margin-bottom: 18px;
}
.photo-box {
    text-align: center;
}
.bukti-foto {
    width: 130px;
    height: 175px;
    object-fit: cover;
    border: 1px solid #1f2937;
    border-radius: 4px;
    display: block;
    background: #f8fafc;
}
.bukti-foto-empty {
    width: 130px;
    height: 175px;
    border: 1px dashed #94a3b8;
    border-radius: 4px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    font-size: 11px;
    color: #64748b;
    background: #f8fafc;
    text-align: center;
    padding: 8px;
    box-sizing: border-box;
}
.section-heading {
    font-size: 12px;
    font-weight: 800;
    color: #0b3970;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-bottom: 1px solid #cbd5e1;
    padding-bottom: 4px;
    margin: 14px 0 8px;
}
.section-heading:first-of-type {
    margin-top: 0;
}
.data-grid {
    width: 100%;
    border-collapse: collapse;
    font-size: 12px;
}
.data-grid td {
    padding: 5px 6px;
    vertical-align: top;
}
.data-grid td.label {
    width: 160px;
    color: #4b5563;
    font-weight: 600;
}
.data-grid td.colon {
    width: 10px;
    text-align: center;
    color: #4b5563;
}
.data-grid td.val {
    color: #111827;
    font-weight: 700;
}

.catatan-box {
    border: 1px solid #cbd5e1;
    background: #f8fafc;
    border-radius: 6px;
    padding: 10px 14px;
    margin-top: 16px;
    font-size: 11px;
    color: #334155;
    line-height: 1.5;
}
.catatan-box b {
    display: block;
    margin-bottom: 4px;
    color: #0b3970;
}
.catatan-box ol {
    margin: 0;
    padding-left: 18px;
}
.catatan-box li {
    margin-bottom: 2px;
}

.signature-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 40px;
    margin-top: 24px;
    text-align: center;
    font-size: 12px;
}
.signature-box {
    padding-top: 6px;
}
.signature-space {
    height: 60px;
}
.signature-name {
    font-weight: 800;
    border-bottom: 1px solid #111827;
    display: inline-block;
    padding: 0 16px 2px;
    color: #111827;
}
.signature-role {
    font-size: 11px;
    color: #4b5563;
    margin-top: 3px;
}

.cetak-footer {
    margin-top: 18px;
    font-size: 10px;
    color: #64748b;
    text-align: right;
    border-top: 1px dashed #cbd5e1;
    padding-top: 6px;
}

/* Tampilan Cetak / Print A4 */
@media print {
    @page {
        size: A4 portrait;
        margin: 10mm 15mm;
    }
    html, body {
        background: #ffffff !important;
        color: #000000 !important;
        font-family: Arial, Helvetica, sans-serif !important;
        font-size: 10pt !important;
        margin: 0 !important;
        padding: 0 !important;
    }
    .auth-header,
    .student-top,
    .student-sidebar,
    .bukti-actions,
    .no-print,
    .alert {
        display: none !important;
    }
    .student-shell {
        max-width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
    }
    .student-layout {
        display: block !important;
    }
    .student-main-card {
        border: none !important;
        box-shadow: none !important;
        padding: 0 !important;
        margin: 0 !important;
        background: transparent !important;
    }
    .bukti-card {
        border: 1.5px solid #000000 !important;
        box-shadow: none !important;
        padding: 16px 20px !important;
        border-radius: 0 !important;
        page-break-inside: avoid !important;
    }
    .kop-sekolah {
        color: #000000 !important;
    }
    .kop-surat {
        border-bottom: 2px solid #000000 !important;
    }
    .nomor-badge-box {
        background: transparent !important;
        border: 1px solid #000000 !important;
    }
    .status-tag {
        border: 1px solid #000000 !important;
        background: transparent !important;
        color: #000000 !important;
    }
    .bukti-foto {
        border: 1px solid #000000 !important;
    }
    .bukti-foto-empty {
        border: 1px dashed #000000 !important;
        color: #000000 !important;
    }
    .section-heading {
        color: #000000 !important;
        border-bottom: 1px solid #000000 !important;
    }
    .catatan-box {
        background: transparent !important;
        border: 1px solid #000000 !important;
        color: #000000 !important;
    }
    .catatan-box b {
        color: #000000 !important;
    }
    .signature-name {
        border-bottom: 1px solid #000000 !important;
        color: #000000 !important;
    }
    .cetak-footer {
        color: #000000 !important;
        border-top: 1px solid #000000 !important;
    }
}
@media(max-width: 760px) {
    .content-layout {
        grid-template-columns: 1fr;
    }
    .photo-box {
        order: -1;
        display: flex;
        justify-content: center;
        margin-bottom: 12px;
    }
    .signature-row {
        grid-template-columns: 1fr;
        gap: 24px;
    }
}
</style>

<!-- Bar Aksi (Hanya Tampil di Layar, Tersembunyi Saat Dicetak) -->
<div class="bukti-actions no-print">
    <div>
        <h2 style="margin:0 0 4px;font-size:22px;color:#092f70">Tanda Bukti Pendaftaran</h2>
        <p class="muted" style="margin:0">Cetak lembar bukti ini sebagai tanda verifikasi fisik pada jadwal PPDB sekolah.</p>
    </div>
    <div class="btn-group">
        <button type="button" onclick="window.print()" class="btn-primary">Cetak Bukti</button>
        <button type="button" onclick="window.print()" class="btn-secondary" title="Gunakan opsi Simpan sebagai PDF pada dialog cetak">Unduh PDF</button>
        <a href="dashboard.php" class="btn-secondary">Kembali ke Dashboard</a>
    </div>
</div>

<?php if ($m = flash('success')): ?>
    <div class="alert alert-success no-print"><?= e($m) ?></div>
<?php endif; ?>

<?php if ($reg['status'] === 'draft'): ?>
    <div class="alert alert-info no-print">
        Perhatian: Pendaftaran ini masih berstatus <b>DRAFT</b>. Pastikan data dan berkas telah lengkap, lalu kirim melalui menu <b>Kirim Pendaftaran</b> agar pendaftaran sah dan dapat diverifikasi oleh sekolah.
    </div>
<?php endif; ?>

<!-- Lembar Bukti Fisik / Formal -->
<div class="bukti-card">
    <!-- KOP Surat Resmi -->
    <div class="kop-surat">
        <p class="kop-instansi">Pemerintah Provinsi Daerah Khusus Ibukota Jakarta · Dinas Pendidikan</p>
        <h1 class="kop-sekolah">SMK NUSANTARA JAKARTA</h1>
        <p class="kop-panitia">PANITIA PENERIMAAN PESERTA DIDIK BARU (PPDB) TAHUN AJARAN <?= e((string)PPDB_TAHUN) ?></p>
        <p class="kop-alamat">Jl. Pendidikan No. 45, Jakarta Selatan | Telp: (021) 7890123 | Laman: https://smknusantara.sch.id</p>
    </div>

    <!-- Judul Dokumen -->
    <div class="judul-dokumen">
        <h2>TANDA BUKTI PENDAFTARAN PPDB ONLINE</h2>
        <p>Tahun Ajaran <?= e((string)PPDB_TAHUN) ?></p>
    </div>

    <!-- Informasi Utama Nomor & Status -->
    <div class="nomor-badge-box">
        <div>
            Nomor Pendaftaran: <b><?= e($reg['nomor_pendaftaran']) ?></b>
        </div>
        <div>
            Tanggal: <b><?= e($tglPendaftaran) ?></b>
        </div>
        <div>
            Status: <span class="status-tag status-<?= e($reg['status']) ?>"><?= e(strtoupper($reg['status'])) ?></span>
        </div>
    </div>

    <!-- Konten Biodata dan Pas Foto -->
    <div class="content-layout">
        <div>
            <div class="section-heading">A. Data Calon Siswa</div>
            <table class="data-grid">
                <tr>
                    <td class="label">Nama Lengkap</td>
                    <td class="colon">:</td>
                    <td class="val"><?= e($siswa['nama_lengkap'] ?? $_SESSION['user']['nama_lengkap']) ?></td>
                </tr>
                <tr>
                    <td class="label">NISN</td>
                    <td class="colon">:</td>
                    <td class="val"><?= e($siswa['nisn'] ?? '-') ?></td>
                </tr>
                <tr>
                    <td class="label">NIK</td>
                    <td class="colon">:</td>
                    <td class="val"><?= e($siswa['nik'] ?? '-') ?></td>
                </tr>
                <tr>
                    <td class="label">Tempat, Tanggal Lahir</td>
                    <td class="colon">:</td>
                    <td class="val"><?= e($siswa['tempat_lahir'] ?? '-') ?>, <?= e($tglLahir) ?></td>
                </tr>
                <tr>
                    <td class="label">Jenis Kelamin</td>
                    <td class="colon">:</td>
                    <td class="val"><?= e($jenisKelaminText) ?></td>
                </tr>
                <tr>
                    <td class="label">Asal Sekolah</td>
                    <td class="colon">:</td>
                    <td class="val"><?= e($siswa['asal_sekolah'] ?? '-') ?></td>
                </tr>
                <tr>
                    <td class="label">Nomor Telepon / HP</td>
                    <td class="colon">:</td>
                    <td class="val"><?= e($siswa['no_hp'] ?? '-') ?></td>
                </tr>
                <tr>
                    <td class="label">Email Pendaftar</td>
                    <td class="colon">:</td>
                    <td class="val"><?= e($siswa['email'] ?? $_SESSION['user']['email'] ?? '-') ?></td>
                </tr>
                <tr>
                    <td class="label">Alamat Lengkap</td>
                    <td class="colon">:</td>
                    <td class="val">
                        <?= e($siswa['alamat'] ?? '-') ?>
                        <?php if (!empty($siswa['kelurahan'])): ?>, Kel. <?= e($siswa['kelurahan']) ?><?php endif; ?>
                        <?php if (!empty($siswa['kecamatan'])): ?>, Kec. <?= e($siswa['kecamatan']) ?><?php endif; ?>
                        <?php if (!empty($siswa['kabupaten'])): ?>, <?= e($siswa['kabupaten']) ?><?php endif; ?>
                        <?php if (!empty($siswa['provinsi'])): ?>, Prov. <?= e($siswa['provinsi']) ?><?php endif; ?>
                        <?php if (!empty($siswa['kode_pos'])): ?> (<?= e($siswa['kode_pos']) ?>)<?php endif; ?>
                    </td>
                </tr>
            </table>

            <div class="section-heading">B. Data Orang Tua / Wali</div>
            <table class="data-grid">
                <tr>
                    <td class="label">Nama Ayah</td>
                    <td class="colon">:</td>
                    <td class="val"><?= e($siswa['nama_ayah'] ?? '-') ?></td>
                </tr>
                <tr>
                    <td class="label">Nama Ibu</td>
                    <td class="colon">:</td>
                    <td class="val"><?= e($siswa['nama_ibu'] ?? '-') ?></td>
                </tr>
                <tr>
                    <td class="label">Nomor Telepon Wali</td>
                    <td class="colon">:</td>
                    <td class="val"><?= e($siswa['no_hp_wali'] ?? '-') ?></td>
                </tr>
            </table>

            <div class="section-heading">C. Pilihan Program Keahlian</div>
            <table class="data-grid">
                <tr>
                    <td class="label">Pilihan Jurusan</td>
                    <td class="colon">:</td>
                    <td class="val"><?= e($reg['nama_jurusan'] ?? '-') ?> (<?= e($reg['kode_jurusan'] ?? '-') ?>)</td>
                </tr>
                <tr>
                    <td class="label">Tahun Ajaran</td>
                    <td class="colon">:</td>
                    <td class="val"><?= e($reg['tahun_ajaran']) ?></td>
                </tr>
            </table>
        </div>

        <!-- Kolom Pas Foto -->
        <div class="photo-box">
            <?php if ($fotoDoc): ?>
                <img src="lihat-foto.php?id=<?= (int)$fotoDoc['id'] ?>" alt="Pas Foto Pendaftar" class="bukti-foto">
                <small style="display:block;margin-top:4px;font-size:10px;color:#4b5563">Pas Foto Resmi 3x4</small>
            <?php else: ?>
                <div class="bukti-foto-empty">
                    <b>Pas Foto 3x4</b>
                    <span>(Belum diunggah)</span>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Catatan dan Petunjuk -->
    <div class="catatan-box">
        <b>Ketentuan dan Petunjuk Verifikasi Berkas:</b>
        <ol>
            <li>Tanda Bukti Pendaftaran ini merupakan dokumen resmi pendaftaran PPDB Online SMK Nusantara Tahun Ajaran <?= e((string)PPDB_TAHUN) ?>.</li>
            <li>Calon siswa wajib mencetak lembar ini dalam ukuran A4 dan membawanya saat jadwal verifikasi dokumen fisik di sekretariat PPDB sekolah.</li>
            <li>Dokumen fisik yang wajib dibawa: Lembar Bukti ini, fotokopi Kartu Keluarga, fotokopi Akta Kelahiran, fotokopi Ijazah/SKL, dan pas foto 3x4 (2 lembar).</li>
            <li>Seluruh data yang dicantumkan telah diisi secara sadar, benar, dan dapat dipertanggungjawabkan sesuai hukum yang berlaku.</li>
        </ol>
    </div>

    <!-- Kolom Tanda Tangan Pengesahan -->
    <div class="signature-row">
        <div class="signature-box">
            <p style="margin:0 0 4px">Mengetahui,</p>
            <p style="margin:0;font-weight:700">Calon Peserta Didik</p>
            <div class="signature-space"></div>
            <div class="signature-name"><?= e($siswa['nama_lengkap'] ?? $_SESSION['user']['nama_lengkap']) ?></div>
            <div class="signature-role">NISN: <?= e($siswa['nisn'] ?? '-') ?></div>
        </div>
        <div class="signature-box">
            <p style="margin:0 0 4px">Jakarta, <?= date('d F Y') ?></p>
            <p style="margin:0;font-weight:700">Panitia PPDB SMK Nusantara</p>
            <div class="signature-space"></div>
            <div class="signature-name">Panitia Verifikasi PPDB</div>
            <div class="signature-role">NIP / ID Verifikator Sekolah</div>
        </div>
    </div>

    <!-- Footer Cetak -->
    <div class="cetak-footer">
        Dicetak secara resmi melalui Sistem Informasi PPDB Online SMK Nusantara pada <?= date('d/m/Y H:i:s') ?> WIB
    </div>
</div>
<?php
student_footer();
