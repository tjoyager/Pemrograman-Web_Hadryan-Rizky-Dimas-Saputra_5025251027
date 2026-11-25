<?php
declare(strict_types=1);
require_once __DIR__.'/includes/bootstrap.php';
require_once __DIR__.'/includes/student_layout.php';
require_student();
$pdo=db(); $uid=(int)$_SESSION['user']['id'];
$stmt=$pdo->prepare('SELECT * FROM siswa WHERE user_id=:uid'); $stmt->execute(['uid'=>$uid]); $siswa=$stmt->fetch() ?: [];
$reg=current_registration($pdo,$uid);
$docs=[];
if($reg){$q=$pdo->prepare('SELECT jenis_dokumen,status_verifikasi FROM dokumen_pendaftaran WHERE pendaftaran_id=:pid');$q->execute(['pid'=>$reg['id']]);foreach($q->fetchAll() as $d){$docs[$d['jenis_dokumen']]=$d;}}
$profileFields=['nik','tempat_lahir','tanggal_lahir','jenis_kelamin','alamat','asal_sekolah'];
$profileComplete=true; foreach($profileFields as $f){if(empty($siswa[$f])){$profileComplete=false;break;}}
$docComplete=count(array_intersect(['foto','kk','akta','ijazah'],array_keys($docs)))===4;
$percent=($profileComplete?35:0)+($reg?20:0)+($docComplete?35:0)+(($reg&&$reg['status']!=='draft')?10:0);
student_header('Dashboard Siswa','dashboard');
?>
<h2>Selamat datang, <?=e($_SESSION['user']['nama_lengkap'])?>!</h2>
<p class="muted">Pantau kelengkapan berkas dan progres pendaftaran PPDB Anda.</p>
<div class="stat-grid">
 <div class="stat-card"><small>Nomor Pendaftaran</small><strong style="font-size:15px"><?=e($reg['nomor_pendaftaran']??'Belum ada')?></strong></div>
 <div class="stat-card"><small>Status Pendaftaran</small><strong style="font-size:15px"><?=e(strtoupper($reg['status']??'Belum dibuat'))?></strong></div>
 <div class="stat-card"><small>Progres Pengisian</small><strong><?=$percent?>%</strong></div>
</div>
<b style="font-size:12px">Progres kelengkapan PPDB</b><div class="progress-track"><span style="width:<?=$percent?>%"></span></div>
<div class="module-list">
 <div class="module-card"><span class="module-icon">♙</span><div><b>Profil Siswa</b><small><?= $profileComplete?'Biodata utama sudah lengkap':'Lengkapi identitas dan alamat siswa' ?></small><a href="profil.php">Kelola Profil →</a></div></div>
 <div class="module-card"><span class="module-icon">▤</span><div><b>Pendaftaran & Jurusan</b><small><?= $reg?'Pilihan: '.e($reg['nama_jurusan']??'-'):'Pilih program keahlian' ?></small><a href="pendaftaran.php">Kelola Pendaftaran →</a></div></div>
 <div class="module-card"><span class="module-icon">▧</span><div><b>Dokumen / Upload Foto</b><small><?=count($docs)?> dari 4 jenis dokumen diunggah</small><a href="dokumen.php">Kelola Dokumen & Foto →</a></div></div>
 <div class="module-card"><span class="module-icon">✓</span><div><b>Kirim Pendaftaran</b><small><?= $reg?e(strtoupper($reg['status'])):'Belum dikirim' ?></small><a href="kirim-pendaftaran.php">Periksa & Kirim →</a></div></div>
 <div class="module-card"><span class="module-icon">⎙</span><div><b>Bukti Pendaftaran</b><small><?= ($reg&&$reg['status']!=='draft')?'Pendaftaran terkirim, siap dicetak':'Cetak bukti pendaftaran siswa' ?></small><a href="bukti-pendaftaran.php">Lihat Bukti Pendaftaran →</a></div></div>
</div>
<?php if($reg): ?>
 <div class="alert alert-info" style="margin-top:18px">Nomor pendaftaran Anda: <b><?=e($reg['nomor_pendaftaran'])?></b>. Simpan nomor ini untuk keperluan komunikasi dengan sekolah. <a href="bukti-pendaftaran.php" style="font-weight:bold;text-decoration:underline;margin-left:8px">Lihat Lembar Bukti Pendaftaran →</a></div>
<?php endif; ?>
<?php student_footer(); ?>
