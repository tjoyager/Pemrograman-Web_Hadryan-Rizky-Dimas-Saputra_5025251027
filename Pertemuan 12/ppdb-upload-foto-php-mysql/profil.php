<?php
declare(strict_types=1);
require_once __DIR__.'/includes/bootstrap.php';
require_once __DIR__.'/includes/student_layout.php';
require_student();
$pdo=db();$uid=(int)$_SESSION['user']['id'];
$q=$pdo->prepare('SELECT * FROM siswa WHERE user_id=:uid');$q->execute(['uid'=>$uid]);$s=$q->fetch()?:[];
$errors=[];
if($_SERVER['REQUEST_METHOD']==='POST'){
 if(!verify_csrf()){$errors[]='Token keamanan tidak valid. Muat ulang halaman.';}
 else{
  $data=[
   'nik'=>trim($_POST['nik']??''),'tempat_lahir'=>trim($_POST['tempat_lahir']??''),
   'tanggal_lahir'=>trim($_POST['tanggal_lahir']??''),'jenis_kelamin'=>trim($_POST['jenis_kelamin']??''),
   'alamat'=>trim($_POST['alamat']??''),'kelurahan'=>trim($_POST['kelurahan']??''),
   'kecamatan'=>trim($_POST['kecamatan']??''),'kabupaten'=>trim($_POST['kabupaten']??''),
   'provinsi'=>trim($_POST['provinsi']??''),'kode_pos'=>trim($_POST['kode_pos']??''),
   'asal_sekolah'=>trim($_POST['asal_sekolah']??''),'nama_ayah'=>trim($_POST['nama_ayah']??''),
   'nama_ibu'=>trim($_POST['nama_ibu']??''),'no_hp_wali'=>trim($_POST['no_hp_wali']??'')
  ];
  if($data['nik']!==''&&!preg_match('/^[0-9]{16}$/',$data['nik']))$errors[]='NIK harus 16 digit angka.';
  if(!in_array($data['jenis_kelamin'],['L','P'],true))$errors[]='Pilih jenis kelamin.';
  if($data['tempat_lahir']===''||$data['tanggal_lahir']===''||$data['alamat']===''||$data['asal_sekolah']==='')$errors[]='Tempat/tanggal lahir, alamat, dan asal sekolah wajib diisi.';
  if(!$errors){
   $sql='UPDATE siswa SET nik=:nik,tempat_lahir=:tempat_lahir,tanggal_lahir=:tanggal_lahir,jenis_kelamin=:jenis_kelamin,alamat=:alamat,kelurahan=:kelurahan,kecamatan=:kecamatan,kabupaten=:kabupaten,provinsi=:provinsi,kode_pos=:kode_pos,asal_sekolah=:asal_sekolah,nama_ayah=:nama_ayah,nama_ibu=:nama_ibu,no_hp_wali=:no_hp_wali WHERE user_id=:uid';
   $st=$pdo->prepare($sql);$data['uid']=$uid;$st->execute($data);
   flash('success','Profil siswa berhasil diperbarui.');redirect('profil.php');
  }
  $s=array_merge($s,$data);
 }
}
student_header('Profil Siswa','profil');
if($m=flash('success'))echo '<div class="alert alert-success">'.e($m).'</div>';
if($errors){echo '<div class="alert alert-error">';foreach($errors as $er)echo '<p>'.e($er).'</p>';echo '</div>';}
?>
<h2>Lengkapi Biodata</h2><p class="muted">Data akun dan NISN berasal dari registrasi awal. Lengkapi informasi berikut sebelum mengirim pendaftaran.</p>
<form method="post" action="profil.php">
<input type="hidden" name="csrf_token" value="<?=e(csrf_token())?>">
<div class="form-grid">
 <div class="form-field"><label>Nama Lengkap</label><input value="<?=e($s['nama_lengkap']??$_SESSION['user']['nama_lengkap'])?>" disabled></div>
 <div class="form-field"><label>NISN</label><input value="<?=e($s['nisn']??'')?>" disabled></div>
 <div class="form-field"><label for="nik">NIK (16 digit)</label><input id="nik" name="nik" maxlength="16" pattern="[0-9]{16}" value="<?=e($s['nik']??'')?>" placeholder="Nomor Induk Kependudukan"></div>
 <div class="form-field"><label for="asal_sekolah">Asal Sekolah *</label><input id="asal_sekolah" name="asal_sekolah" required value="<?=e($s['asal_sekolah']??'')?>"></div>
 <div class="form-field"><label for="tempat_lahir">Tempat Lahir *</label><input id="tempat_lahir" name="tempat_lahir" required value="<?=e($s['tempat_lahir']??'')?>"></div>
 <div class="form-field"><label for="tanggal_lahir">Tanggal Lahir *</label><input id="tanggal_lahir" type="date" name="tanggal_lahir" required value="<?=e($s['tanggal_lahir']??'')?>"></div>
 <div class="form-field"><label for="jenis_kelamin">Jenis Kelamin *</label><select id="jenis_kelamin" name="jenis_kelamin" required><option value="">Pilih</option><option value="L" <?=($s['jenis_kelamin']??'')==='L'?'selected':''?>>Laki-laki</option><option value="P" <?=($s['jenis_kelamin']??'')==='P'?'selected':''?>>Perempuan</option></select></div>
 <div class="form-field full"><label for="alamat">Alamat Lengkap *</label><textarea id="alamat" name="alamat" required><?=e($s['alamat']??'')?></textarea></div>
 <div class="form-field"><label for="kelurahan">Kelurahan/Desa</label><input id="kelurahan" name="kelurahan" value="<?=e($s['kelurahan']??'')?>"></div>
 <div class="form-field"><label for="kecamatan">Kecamatan</label><input id="kecamatan" name="kecamatan" value="<?=e($s['kecamatan']??'')?>"></div>
 <div class="form-field"><label for="kabupaten">Kabupaten/Kota</label><input id="kabupaten" name="kabupaten" value="<?=e($s['kabupaten']??'')?>"></div>
 <div class="form-field"><label for="provinsi">Provinsi</label><input id="provinsi" name="provinsi" value="<?=e($s['provinsi']??'')?>"></div>
 <div class="form-field"><label for="kode_pos">Kode Pos</label><input id="kode_pos" name="kode_pos" value="<?=e($s['kode_pos']??'')?>"></div>
 <div class="form-field"><label for="nama_ayah">Nama Ayah</label><input id="nama_ayah" name="nama_ayah" value="<?=e($s['nama_ayah']??'')?>"></div>
 <div class="form-field"><label for="nama_ibu">Nama Ibu</label><input id="nama_ibu" name="nama_ibu" value="<?=e($s['nama_ibu']??'')?>"></div>
 <div class="form-field"><label for="no_hp_wali">Nomor HP Orang Tua/Wali</label><input id="no_hp_wali" name="no_hp_wali" value="<?=e($s['no_hp_wali']??'')?>"></div>
</div>
<div class="form-actions"><button class="btn-primary" type="submit">Simpan Profil</button><a class="btn-secondary" href="dashboard.php">Kembali ke Dashboard</a></div>
</form>
<?php student_footer(); ?>
