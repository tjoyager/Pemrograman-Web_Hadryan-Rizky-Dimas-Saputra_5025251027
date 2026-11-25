<?php
declare(strict_types=1);
require_once __DIR__.'/includes/bootstrap.php';
require_once __DIR__.'/includes/student_layout.php';
require_student();
$pdo=db();$uid=(int)$_SESSION['user']['id'];$reg=current_registration($pdo,$uid);$errors=[];$checks=[];
if(!$reg){flash('error','Pilih jurusan terlebih dahulu sebelum mengirim pendaftaran.');redirect('pendaftaran.php');}
$q=$pdo->prepare('SELECT * FROM siswa WHERE user_id=:uid');$q->execute(['uid'=>$uid]);$s=$q->fetch()?:[];
$required=['nik'=>'NIK','tempat_lahir'=>'Tempat lahir','tanggal_lahir'=>'Tanggal lahir','jenis_kelamin'=>'Jenis kelamin','alamat'=>'Alamat','asal_sekolah'=>'Asal sekolah'];
foreach($required as $field=>$label)$checks[$label]=!empty($s[$field]);
$dq=$pdo->prepare('SELECT jenis_dokumen FROM dokumen_pendaftaran WHERE pendaftaran_id=:pid');$dq->execute(['pid'=>$reg['id']]);$uploaded=$dq->fetchAll(PDO::FETCH_COLUMN);
foreach(['foto'=>'Pas foto','kk'=>'Kartu Keluarga','akta'=>'Akta Kelahiran','ijazah'=>'Ijazah/SKL'] as $k=>$label)$checks[$label]=in_array($k,$uploaded,true);
$checks['Pilihan jurusan']=!empty($reg['jurusan_id']);
$allReady=!in_array(false,$checks,true);
if($_SERVER['REQUEST_METHOD']==='POST'){
 if(!verify_csrf())$errors[]='Token keamanan tidak valid.';
 elseif($reg['status']!=='draft')$errors[]='Pendaftaran ini sudah pernah dikirim.';
 elseif(!$allReady)$errors[]='Masih ada data profil atau dokumen yang belum lengkap.';
 else{
  try{
   $pdo->beginTransaction();
   $st=$pdo->prepare('UPDATE pendaftaran SET status="submitted",tanggal_kirim=NOW() WHERE id=:id AND user_id=:uid AND status="draft"');
   $st->execute(['id'=>$reg['id'],'uid'=>$uid]);
   if($st->rowCount()!==1)throw new RuntimeException('Status pendaftaran berubah. Muat ulang halaman.');
   $hist=$pdo->prepare('INSERT INTO riwayat_status(pendaftaran_id,status_lama,status_baru,keterangan,diubah_oleh) VALUES(:pid,"draft","submitted","Dikirim oleh calon siswa",:uid)');
   $hist->execute(['pid'=>$reg['id'],'uid'=>$uid]);
   $pdo->commit();
   flash('success','Pendaftaran berhasil dikirim dan menunggu verifikasi admin.');
   redirect('bukti-pendaftaran.php');
  }catch(Throwable $ex){if($pdo->inTransaction())$pdo->rollBack();error_log($ex->getMessage());$errors[]='Pendaftaran gagal dikirim. Silakan coba kembali.';}
 }
}
student_header('Periksa & Kirim Pendaftaran','pendaftaran');
if($m=flash('success'))echo '<div class="alert alert-success">'.e($m).'</div>';
if($errors){echo '<div class="alert alert-error">';foreach($errors as $er)echo '<p>'.e($er).'</p>';echo '</div>';}
?>
<h2>Finalisasi Pendaftaran</h2>
<p class="muted">Pastikan seluruh data sudah benar. Setelah dikirim, siswa tidak dapat mengubah profil, jurusan, atau dokumen sampai admin membuka prosedur koreksi.</p>
<div class="alert alert-info">Nomor pendaftaran: <b><?=e($reg['nomor_pendaftaran'])?></b> · Jurusan: <b><?=e($reg['nama_jurusan']??'-')?></b> · Tahun ajaran: <b><?=e($reg['tahun_ajaran'])?></b></div>
<h3 style="font-size:14px;color:#153b70">Checklist kelengkapan</h3>
<div class="check-list">
<?php foreach($checks as $label=>$ok): ?><div class="check-item"><span class="<?=$ok?'check':'check missing'?>"><?=$ok?'✓':'!'?></span><?=e($label)?> — <?=$ok?'Lengkap':'Belum lengkap'?></div><?php endforeach; ?>
</div>
<?php if($reg['status']==='draft'): ?>
 <form method="post" action="kirim-pendaftaran.php">
  <input type="hidden" name="csrf_token" value="<?=e(csrf_token())?>">
  <div class="form-actions"><button class="btn-primary" type="submit" <?=$allReady?'':'disabled style="opacity:.5;cursor:not-allowed"'?>>Kirim Pendaftaran</button><a class="btn-secondary" href="dashboard.php">Kembali</a></div>
 </form>
<?php else: ?>
 <div class="alert alert-success">Status saat ini: <b><?=e(strtoupper($reg['status']))?></b>. Pendaftaran sudah dikirim.</div>
 <div class="form-actions"><a class="btn-primary" href="bukti-pendaftaran.php">Lihat & Cetak Bukti Pendaftaran</a><a class="btn-secondary" href="dashboard.php">Kembali ke Dashboard</a></div>
<?php endif; ?>
<?php student_footer(); ?>
