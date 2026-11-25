<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/bootstrap.php';

if (!empty($_SESSION['user']) && ($_SESSION['user']['role'] ?? '') === 'siswa') {
    redirect('dashboard.php');
}

$errors = [];
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf()) {
        $errors[] = 'Sesi formulir tidak valid. Muat ulang halaman dan coba kembali.';
    } else {
        $email = strtolower(trim((string)($_POST['email'] ?? '')));
        $password = (string)($_POST['password'] ?? '');

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Masukkan alamat email yang valid.';
        }
        if ($password === '') {
            $errors[] = 'Password wajib diisi.';
        }

        if (!$errors) {
            try {
                $stmt = db()->prepare(
                    'SELECT id, nama_lengkap, email, password_hash, role
                     FROM users
                     WHERE email = :email
                     LIMIT 1'
                );
                $stmt->execute(['email' => $email]);
                $user = $stmt->fetch();

                if (!$user || !password_verify($password, $user['password_hash']) || $user['role'] !== 'siswa') {
                    $errors[] = 'Email atau password tidak sesuai.';
                } else {
                    session_regenerate_id(true);
                    $_SESSION['user'] = [
                        'id'           => (int)$user['id'],
                        'nama_lengkap' => $user['nama_lengkap'],
                        'email'        => $user['email'],
                        'role'         => $user['role'],
                    ];
                    unset($_SESSION['csrf_token']);
                    redirect('dashboard.php');
                }
            } catch (PDOException $ex) {
                error_log($ex->getMessage());
                $errors[] = 'Layanan database belum siap. Periksa konfigurasi database.';
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
  <title>Login Siswa | PPDB Online SMK Nusantara</title>
  <link rel="stylesheet" href="auth.css">
</head>
<body>
<header class="auth-header">
  <a class="brand" href="index.html"><span class="brand-logo">⌂</span><span>SMK<br><b>Nusantara</b></span></a>
  <a class="back-link" href="index.html">← Kembali ke Beranda</a>
</header>
<main class="auth-layout">
  <section class="welcome-panel">
    <div class="welcome-content">
      <span class="tag">PPDB ONLINE 2026/2027</span>
      <h1>Selamat Datang<br>Calon Siswa!</h1>
      <p>Masuk ke akun PPDB Online SMK Nusantara untuk melanjutkan proses pendaftaran dan memantau status seleksi.</p>
      <div class="welcome-points">
        <div><span>✓</span> Pantau status pendaftaran</div>
        <div><span>✓</span> Perbarui biodata dan dokumen</div>
        <div><span>✓</span> Lihat informasi hasil seleksi</div>
      </div>
    </div>
    <div class="decor-circle circle-one"></div><div class="decor-circle circle-two"></div>
    <div class="school-illustration" aria-hidden="true"><div class="ill-cloud"></div><div class="ill-roof"></div>
      <div class="ill-building"><div class="ill-sign">SMK NUSANTARA</div><div class="ill-windows"><i></i><i></i><i></i></div><div class="ill-door"></div></div><div class="ill-ground"></div>
    </div>
  </section>
  <section class="form-panel">
    <div class="form-card">
      <div class="form-brand-mark">SN</div>
      <p class="form-eyebrow">PORTAL PESERTA DIDIK</p>
      <h2>Login Siswa</h2>
      <p class="form-intro">Silakan masuk menggunakan email dan password yang telah didaftarkan.</p>

      <?php if ($errors): ?>
        <div class="alert alert-error" role="alert">
          <?php foreach ($errors as $error): ?><p><?= e($error) ?></p><?php endforeach; ?>
        </div>
      <?php endif; ?>
      <?php if ($message = flash('success')): ?><div class="alert alert-success"><?= e($message) ?></div><?php endif; ?>
      <?php if ($message = flash('error')): ?><div class="alert alert-error"><?= e($message) ?></div><?php endif; ?>

      <form action="login.php" method="post">
        <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
        <label for="email">Email</label>
        <input id="email" name="email" type="email" placeholder="nama@email.com" value="<?= e($email) ?>" autocomplete="username" required>
        <label for="password">Password</label>
        <input id="password" name="password" type="password" placeholder="Masukkan password" autocomplete="current-password" required>
        <div class="form-options">
          <label class="remember"><input type="checkbox" name="remember" value="1"><span>Ingat saya</span></label>
          <span class="text-link" title="Hubungi admin sekolah untuk bantuan reset password">Lupa password?</span>
        </div>
        <button class="submit-button" type="submit">Masuk ke Akun <span>→</span></button>
      </form>
      <div class="form-divider"><span>Belum memiliki akun?</span></div>
      <a class="outline-button" href="register.php">Registrasi Siswa</a>
      <p class="back-home"><a href="index.html">← Kembali ke halaman utama</a></p>
    </div>
    <p class="copyright">© 2026 SMK Nusantara · PPDB Online</p>
  </section>
</main>
</body>
</html>
