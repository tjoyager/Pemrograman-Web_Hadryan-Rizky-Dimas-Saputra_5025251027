PPDB Online SMK Nusantara — Integrasi Modul Siswa (PHP + MySQL)

FITUR
1. Dashboard siswa: ringkasan progres, nomor dan status pendaftaran.
2. Profil siswa: NIK, tempat/tanggal lahir, jenis kelamin, alamat, asal sekolah, data orang tua.
3. Pendaftaran: membuat satu draft pendaftaran per siswa per tahun ajaran dan memilih satu jurusan.
4. Upload dokumen: pas foto, KK, akta kelahiran, ijazah/SKL; PDF/JPG/PNG, maks 2 MB/file.
5. Kirim pendaftaran: checklist server-side; hanya dapat dikirim jika profil, jurusan, dan 4 dokumen lengkap.
6. Riwayat status: pencatatan draft -> submitted.
7. Data upload disimpan dengan nama acak di storage/dokumen dan diblokir dari akses langsung oleh .htaccess.

INSTALASI BARU
1. Ekstrak folder ini ke C:\xampp\htdocs\ppdb-online-smk-nusantara-integrasi
2. Jalankan Apache dan MySQL.
3. Buka phpMyAdmin dan import database.sql.
4. Periksa config/database.php (default XAMPP root dengan password kosong).
5. Buka http://localhost/ppdb-online-smk-nusantara-integrasi/
6. Registrasi akun, login, lengkapi Profil, pilih Jurusan, upload semua dokumen, lalu Kirim Pendaftaran.

UPGRADE DARI PROJECT AUTENTIKASI SEBELUMNYA
1. Backup database db_ppdb_smk terlebih dahulu.
2. Import upgrade-existing.sql satu kali untuk menambahkan kolom profil dan tabel modul baru.
3. Salin semua file project ini untuk menggantikan file project lama.
4. Jangan import database.sql ke database lama jika tidak ingin mempertahankan/mengubah skema; gunakan upgrade-existing.sql.
5. Periksa nama tabel dan kolom yang sudah ada. Upgrade SQL mengasumsikan users dan siswa awal memiliki kolom id, user_id, nisn, nama_lengkap, no_hp dan users memiliki id, nama_lengkap, email, password_hash, role.

STRUKTUR TABEL
- users: akun autentikasi.
- siswa: biodata 1:1 terhadap users.
- jurusan: program keahlian dan kuota.
- pendaftaran: satu pendaftaran per siswa per tahun ajaran.
- dokumen_pendaftaran: satu file per jenis dokumen per pendaftaran.
- riwayat_status: histori perubahan status.

CATATAN
- Daftar jurusan dan kuota dalam database.sql adalah data contoh; sesuaikan dengan jurusan resmi SMK Nusantara.
- Tahun ajaran diatur pada includes/bootstrap.php melalui PPDB_TAHUN.
- MVP ini hanya membuat status submitted. Verifikasi dan hasil seleksi dilakukan melalui modul admin yang belum termasuk paket ini.
- Pemeriksaan MIME upload memakai PHP Fileinfo. Pastikan extension fileinfo aktif.
- Untuk produksi, gunakan HTTPS, batasi akses folder storage, backup rutin, dan lakukan audit keamanan.
- Bila tabel siswa Anda sudah memiliki struktur berbeda, cocokkan upgrade-existing.sql dengan skema aktual sebelum dijalankan.
