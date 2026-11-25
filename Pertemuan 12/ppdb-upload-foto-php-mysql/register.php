<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/bootstrap.php';

if (!empty($_SESSION['user']) && ($_SESSION['user']['role'] ?? '') === 'siswa') {
    redirect('dashboard.php');
}

$errors = [];
$fullname = $email = $phone = $nisn = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf()) {
        $errors[] = 'Sesi formulir tidak valid. Muat ulang halaman dan coba kembali.';
    } else {
        $fullname = trim((string)($_POST['fullname'] ?? ''));
        $email = strtolower(trim((string)($_POST['email'] ?? '')));
        $phone = trim((string)($_POST['phone'] ?? ''));
        $nisn = trim((string)($_POST['nisn'] ?? ''));
        $password = (string)($_POST['password'] ?? '');
        $confirmPassword = (string)($_POST['confirm_password'] ?? '');
        $agreed = isset($_POST['agree']);

        if ($fullname === '' || mb_strlen($fullname) < 3) {
            $errors[] = 'Nama lengkap wajib diisi (minimal 3 karakter).';
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Masukkan alamat email yang valid.';
        }
        if (!preg_match('/^[0-9]{9,15}$/', preg_replace('/\D+/', '', $phone))) {
            $errors[] = 'Nomor HP/WhatsApp harus terdiri dari 9–15 digit.';
        }
        if (!preg_match('/^[0-9]{10}$/', $nisn)) {
            $errors[] = 'NISN harus terdiri dari tepat 10 digit angka.';
        }
        if (strlen($password) < 8) {
            $errors[] = 'Password minimal 8 karakter.';
        }
        if ($password !== $confirmPassword) {
            $errors[] = 'Konfirmasi password tidak sama.';
        }
        if (!$agreed) {
            $errors[] = 'Anda harus menyetujui pernyataan data.';
        }

        if (!$errors) {
            try {
                $pdo = db();
                $pdo->beginTransaction();

                $check = $pdo->prepare(
                    'SELECT
                        EXISTS(SELECT 1 FROM users WHERE email = :email) AS email_exists,
                        EXISTS(SELECT 1 FROM siswa WHERE nisn = :nisn) AS nisn_exists'
                );
                $check->execute(['email' => $email, 'nisn' => $nisn]);
                $existing = $check->fetch();

                if ((int)$existing['email_exists'] === 1) {
                    $errors[] = 'Email sudah terdaftar. Silakan login menggunakan akun tersebut.';
                }
                if ((int)$existing['nisn_exists'] === 1) {
                    $errors[] = 'NISN sudah digunakan pada akun lain. Periksa kembali data Anda.';
                }

                if ($errors) {
                    $pdo->rollBack();
                } else {
                    $hash = password_hash($password, PASSWORD_DEFAULT);

                    $userStmt = $pdo->prepare(
                        'INSERT INTO users (nama_lengkap, email, password_hash, role)
                         VALUES (:nama_lengkap, :email, :password_hash, "siswa")'
                    );
                    $userStmt->execute([
                        'nama_lengkap' => $fullname,
                        'email' => $email,
                        'password_hash' => $hash,
                    ]);
                    $userId = (int)$pdo->lastInsertId();

                    $studentStmt = $pdo->prepare(
                        'INSERT INTO siswa (user_id, nisn, nama_lengkap, no_hp)
                         VALUES (:user_id, :nisn, :nama_lengkap, :no_hp)'
                    );
                    $studentStmt->execute([
                        'user_id' => $userId,
                        'nisn' => $nisn,
                        'nama_lengkap' => $fullname,
                        'no_hp' => preg_replace('/\D+/', '', $phone),
                    ]);

                    $pdo->commit();
                    flash('success', 'Registrasi berhasil. Silakan login menggunakan email dan password Anda.');
                    redirect('login.php');
                }
            } catch (PDOException $ex) {
                if (isset($pdo) && $pdo->inTransaction()) {
                    $pdo->rollBack();
                }
                error_log($ex->getMessage());
                if ($ex->getCode() === '23000') {
                    $errors[] = 'Email atau NISN sudah terdaftar. Periksa kembali data Anda.';
                } else {
                    $errors[] = 'Registrasi belum dapat diproses. Periksa struktur database dan konfigurasi.';
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registrasi Siswa | PPDB Online SMK Nusantara</title>
  <link rel="stylesheet" href="auth.css">
</head>
<body>
<header class="auth-header">
  <a class="brand" href="index.html"><span class="brand-logo">⌂</span><span>SMK<br><b>Nusantara</b></span></a>
  <a class="back-link" href="index.html">← Kembali ke Beranda</a>
</header>
<main class="auth-layout register-layout">
  <section class="welcome-panel">
    <div class="welcome-content">
      <span class="tag">PPDB ONLINE 2026/2027</span>
      <h1>Mulai Langkah<br>Menuju Masa Depan!</h1>
      <p>Buat akun calon siswa untuk memulai pendaftaran di SMK Nusantara. Pastikan data yang dimasukkan benar dan aktif.</p>
      <div class="welcome-points"><div><span>1</span> Buat akun menggunakan email aktif</div><div><span>2</span> Lengkapi biodata calon siswa</div><div><span>3</span> Lanjutkan proses pendaftaran</div></div>
    </div>
    <div class="decor-circle circle-one"></div><div class="decor-circle circle-two"></div>
    <div class="school-illustration" aria-hidden="true"><div class="ill-cloud"></div><div class="ill-roof"></div>
      <div class="ill-building"><div class="ill-sign">SMK NUSANTARA</div><div class="ill-windows"><i></i><i></i><i></i></div><div class="ill-door"></div></div><div class="ill-ground"></div>
    </div>
  </section>
  <section class="form-panel">
    <div class="form-card register-card">
      <div class="form-brand-mark">SN</div><p class="form-eyebrow">PORTAL PESERTA DIDIK</p>
      <h2>Registrasi Siswa</h2><p class="form-intro">Isi data berikut untuk membuat akun PPDB Online.</p>
      <?php if ($errors): ?>
        <div class="alert alert-error" role="alert">
          <?php foreach ($errors as $error): ?><p><?= e($error) ?></p><?php endforeach; ?>
        </div>
      <?php endif; ?>
      <form action="register.php" method="post">
        <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
        <div class="field-grid">
          <div class="field full"><label for="fullname">Nama Lengkap</label><input id="fullname" name="fullname" type="text" placeholder="Masukkan nama sesuai identitas" value="<?= e($fullname) ?>" autocomplete="name" required></div>
          <div class="field"><label for="email">Email Aktif</label><input id="email" name="email" type="email" placeholder="nama@email.com" value="<?= e($email) ?>" autocomplete="email" required></div>
          <div class="field"><label for="phone">Nomor HP / WhatsApp</label><input id="phone" name="phone" type="tel" placeholder="08xxxxxxxxxx" value="<?= e($phone) ?>" autocomplete="tel" required></div>
          <div class="field full"><label for="nisn">NISN</label><input id="nisn" name="nisn" type="text" inputmode="numeric" placeholder="Masukkan 10 digit NISN" value="<?= e($nisn) ?>" pattern="[0-9]{10}" maxlength="10" required><small class="field-hint">NISN terdiri dari 10 digit angka.</small></div>
          <div class="field"><label for="password">Password</label><input id="password" name="password" type="password" placeholder="Minimal 8 karakter" minlength="8" autocomplete="new-password" required></div>
          <div class="field"><label for="confirm-password">Konfirmasi Password</label><input id="confirm-password" name="confirm_password" type="password" placeholder="Ulangi password" minlength="8" autocomplete="new-password" required></div>
        </div>
        <label class="terms"><input type="checkbox" name="agree" value="1" required><span>Saya menyatakan data yang diisi benar dan bersedia mengikuti ketentuan PPDB SMK Nusantara.</span></label>
        <button class="submit-button" type="submit">Buat Akun <span>→</span></button>
      </form>
      <div class="form-divider"><span>Sudah memiliki akun?</span></div>
      <a class="outline-button" href="login.php">Login Siswa</a>
      <p class="back-home"><a href="index.html">← Kembali ke halaman utama</a></p>
    </div>
    <p class="copyright">© 2026 SMK Nusantara · PPDB Online</p>
  </section>
</main>
</body>
</html>
