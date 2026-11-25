<?php
function student_header(string $title, string $active): void {
    $name = $_SESSION['user']['nama_lengkap'] ?? 'Siswa';
    ?>
    <!DOCTYPE html><html lang="id"><head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title) ?> | PPDB Online SMK Nusantara</title><link rel="stylesheet" href="auth.css">
    </head><body>
    <header class="auth-header">
      <a class="brand" href="dashboard.php"><span class="brand-logo">⌂</span><span>SMK<br><b>Nusantara</b></span></a>
      <a class="back-link" href="logout.php">Logout</a>
    </header>
    <main class="student-shell">
      <div class="student-top"><div><h1><?= e($title) ?></h1><p>PPDB Online SMK Nusantara · Tahun Ajaran <?= e((string)PPDB_TAHUN) ?></p></div><p>Halo, <b><?= e($name) ?></b></p></div>
      <div class="student-layout">
        <aside class="student-sidebar"><nav class="student-nav">
          <a class="<?= $active==='dashboard'?'active':'' ?>" href="dashboard.php">▦ Dashboard</a>
          <a class="<?= $active==='profil'?'active':'' ?>" href="profil.php">♙ Profil Siswa</a>
          <a class="<?= $active==='pendaftaran'?'active':'' ?>" href="pendaftaran.php">▤ Pendaftaran</a>
          <a class="<?= $active==='dokumen'?'active':'' ?>" href="dokumen.php">▧ Dokumen / Upload Foto</a>
          <a class="<?= $active==='bukti'?'active':'' ?>" href="bukti-pendaftaran.php">⎙ Bukti Pendaftaran</a>
          <a class="nav-logout" href="logout.php">↪ Keluar</a>
        </nav></aside>
        <section class="student-main-card">
    <?php
}
function student_footer(): void {
    echo '</section></div></main></body></html>';
}
function current_registration(PDO $pdo, int $userId): ?array {
    $stmt = $pdo->prepare(
        'SELECT p.*, j.nama_jurusan, j.kode_jurusan
         FROM pendaftaran p LEFT JOIN jurusan j ON j.id=p.jurusan_id
         WHERE p.user_id=:uid AND p.tahun_ajaran=:tahun
         LIMIT 1'
    );
    $stmt->execute(['uid'=>$userId, 'tahun'=>PPDB_TAHUN]);
    $row = $stmt->fetch();
    return $row ?: null;
}
