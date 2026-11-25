<?php
declare(strict_types=1);
require_once __DIR__.'/includes/bootstrap.php';
require_once __DIR__.'/includes/student_layout.php';
require_student();
$pdo=db();$uid=(int)$_SESSION['user']['id'];$errors=[];
$majors=$pdo->query('SELECT id,kode_jurusan,nama_jurusan,deskripsi,kuota FROM jurusan WHERE is_active=1 ORDER BY nama_jurusan')->fetchAll();
$reg=current_registration($pdo,$uid);
if($_SERVER['REQUEST_METHOD']==='POST'){
 if(!verify_csrf())$errors[]='Token keamanan tidak valid.';
 elseif($reg&&$reg['status']!=='draft')$errors[]='Pendaftaran sudah dikirim dan tidak dapat diubah oleh siswa.';
 else{
  $jid=(int)($_POST['jurusan_id']??0);
  $found=false;foreach($majors as $m){if((int)$m['id']===$jid){$found=true;break;}}
  if(!$found)$errors[]='Pilih jurusan yang tersedia.';
  if(!$errors){
   if($reg){
    $st=$pdo->prepare('UPDATE pendaftaran SET jurusan_id=:jid WHERE id=:id AND status="draft"');$st->execute(['jid'=>$jid,'id'=>$reg['id']]);
   }else{
    $number='PPDB-'.date('Y').'-'.strtoupper(bin2hex(random_bytes(4)));
    $st=$pdo->prepare('INSERT INTO pendaftaran(user_id,jurusan_id,tahun_ajaran,nomor_pendaftaran,status) VALUES(:uid,:jid,:tahun,:nomor,"draft")');
    $st->execute(['uid'=>$uid,'jid'=>$jid,'tahun'=>PPDB_TAHUN,'nomor'=>$number]);
    $pid=(int)$pdo->lastInsertId();
    $hist=$pdo->prepare('INSERT INTO riwayat_status(pendaftaran_id,status_lama,status_baru,keterangan,diubah_oleh) VALUES(:pid,NULL,"draft","Pendaftaran dibuat oleh siswa",:uid)');
    $hist->execute(['pid'=>$pid,'uid'=>$uid]);
   }
   flash('success','Pilihan jurusan berhasil disimpan.');redirect('pendaftaran.php');
  }
 }
}
$reg=current_registration($pdo,$uid);
student_header('Pendaftaran & Pilihan Jurusan','pendaftaran');
if($m=flash('success'))echo '<div class="alert alert-success">'.e($m).'</div>';
if($errors){echo '<div class="alert alert-error">';foreach($errors as $er)echo '<p>'.e($er).'</p>';echo '</div>';}
?>
<h2>Pilih Program Keahlian</h2><p class="muted">Pilih satu jurusan untuk pendaftaran Tahun Ajaran <?=e(PPDB_TAHUN)?>. Pada MVP, setiap siswa hanya dapat memilih satu jurusan.</p>
<?php if($reg): ?>
 <div class="alert alert-info">Nomor pendaftaran: <b><?=e($reg['nomor_pendaftaran'])?></b> · Status: <b><?=e(strtoupper($reg['status']))?></b></div>
<?php endif; ?>
<?php if(!$majors): ?><div class="empty-state">Belum ada jurusan aktif. Hubungi administrator sekolah.</div>
<?php else: ?>
<form method="post" action="pendaftaran.php">
<input type="hidden" name="csrf_token" value="<?=e(csrf_token())?>">
<div class="jurusan-grid">
<?php foreach($majors as $m): ?>
<label class="jurusan-option">
 <input type="radio" name="jurusan_id" value="<?=$m['id']?>" required <?=((int)($reg['jurusan_id']??0)===(int)$m['id'])?'checked':''?> <?=($reg&&$reg['status']!=='draft')?'disabled':''?>>
 <span><b><?=e($m['nama_jurusan'])?> (<?=e($m['kode_jurusan'])?>)</b><small><?=e($m['deskripsi']??'Program keahlian SMK Nusantara')?></small><small>Kuota: <?= (int)$m['kuota'] ?> siswa</small></span>
</label>
<?php endforeach; ?>
</div>
<?php if(!$reg||$reg['status']==='draft'): ?><div class="form-actions"><button class="btn-primary" type="submit">Simpan Pilihan Jurusan</button></div><?php endif; ?>
</form>
<?php endif; ?>
<?php student_footer(); ?>
