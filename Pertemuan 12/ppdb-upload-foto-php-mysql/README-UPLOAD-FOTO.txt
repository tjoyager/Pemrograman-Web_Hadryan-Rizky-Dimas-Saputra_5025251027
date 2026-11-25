FITUR UPLOAD FOTO PPDB ONLINE SMK NUSANTARA
==========================================

PAKET INI
- dokumen.php       : upload foto + dokumen, preview, ganti file, validasi server.
- lihat-foto.php    : menampilkan foto dengan pemeriksaan login dan kepemilikan data.
- database.sql      : skema database proyek (termasuk dokumen_pendaftaran).
- config/           : koneksi database.
- includes/         : bootstrap session, CSRF, dan layout siswa.
- storage/          : penyimpanan file yang diblokir dari akses URL langsung.

CARA MEMASANG DI XAMPP
1. Ekstrak folder proyek ke C:\xampp\htdocs\ppdb-online-smk\
2. Pastikan folder config, includes, dan storage ikut tersalin.
3. Salin dokumen.php dan lihat-foto.php dari paket ini ke folder proyek.
4. Pastikan config/database.php sesuai konfigurasi MySQL XAMPP:
   DB_HOST=127.0.0.1, DB_NAME=db_ppdb_smk, DB_USER=root, DB_PASS=''
5. Import database.sql melalui phpMyAdmin jika database belum dibuat.
6. Jalankan Apache dan MySQL dari XAMPP Control Panel.
7. Login sebagai siswa, buat draft pendaftaran dan pilih jurusan.
8. Buka http://localhost/ppdb-online-smk/dokumen.php
9. Pilih foto JPG/PNG maksimal 2 MB lalu klik Upload Foto.

MEKANISME
- File foto disimpan di storage/dokumen/ dengan nama acak.
- Metadata disimpan pada dokumen_pendaftaran dengan jenis_dokumen='foto'.
- Foto ditampilkan melalui lihat-foto.php; akses dibatasi ke pemilik pendaftaran.
- Foto dapat diganti selama status pendaftaran masih draft.
- Foto lama dihapus setelah metadata file baru berhasil tersimpan.

PENTING
- Tabel dokumen_pendaftaran harus memiliki kolom:
  pendaftaran_id, jenis_dokumen, nama_file_asli, nama_file_simpan,
  mime_type, ukuran_file, status_verifikasi, created_at, updated_at.
- Harus ada UNIQUE KEY pada (pendaftaran_id, jenis_dokumen), sebagaimana database.sql.
- Folder storage/dokumen harus dapat ditulis oleh Apache/PHP.
- PHP extensions fileinfo dan pdo_mysql harus aktif.
- Batas 2 MB juga perlu didukung konfigurasi php.ini (upload_max_filesize dan post_max_size).
- Untuk deployment di luar localhost, gunakan HTTPS dan tinjau ulang kebijakan penyimpanan serta backup.

UJI SINTAKS
File PHP di paket ini telah diperiksa dengan php -l.
Integrasi database langsung perlu diuji pada XAMPP milik pengguna.
