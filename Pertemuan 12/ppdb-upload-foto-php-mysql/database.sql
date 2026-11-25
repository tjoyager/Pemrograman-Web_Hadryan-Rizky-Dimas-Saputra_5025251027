CREATE DATABASE IF NOT EXISTS db_ppdb_smk CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE db_ppdb_smk;

CREATE TABLE IF NOT EXISTS users (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 nama_lengkap VARCHAR(150) NOT NULL,
 email VARCHAR(190) NOT NULL UNIQUE,
 password_hash VARCHAR(255) NOT NULL,
 role ENUM('admin','siswa') NOT NULL DEFAULT 'siswa',
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS jurusan (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 kode_jurusan VARCHAR(20) NOT NULL UNIQUE,
 nama_jurusan VARCHAR(120) NOT NULL,
 deskripsi TEXT NULL,
 kuota INT UNSIGNED NOT NULL DEFAULT 0,
 is_active TINYINT(1) NOT NULL DEFAULT 1,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS siswa (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 user_id BIGINT UNSIGNED NOT NULL UNIQUE,
 nisn CHAR(10) NOT NULL UNIQUE,
 nama_lengkap VARCHAR(150) NOT NULL,
 no_hp VARCHAR(20) NOT NULL,
 nik CHAR(16) NULL,
 tempat_lahir VARCHAR(100) NULL,
 tanggal_lahir DATE NULL,
 jenis_kelamin ENUM('L','P') NULL,
 alamat TEXT NULL,
 kelurahan VARCHAR(100) NULL,
 kecamatan VARCHAR(100) NULL,
 kabupaten VARCHAR(100) NULL,
 provinsi VARCHAR(100) NULL,
 kode_pos VARCHAR(10) NULL,
 asal_sekolah VARCHAR(180) NULL,
 nama_ayah VARCHAR(150) NULL,
 nama_ibu VARCHAR(150) NULL,
 no_hp_wali VARCHAR(20) NULL,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 CONSTRAINT fk_siswa_user FOREIGN KEY(user_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS pendaftaran (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 user_id BIGINT UNSIGNED NOT NULL,
 jurusan_id BIGINT UNSIGNED NOT NULL,
 tahun_ajaran VARCHAR(9) NOT NULL,
 nomor_pendaftaran VARCHAR(40) NOT NULL UNIQUE,
 status ENUM('draft','submitted','verified','accepted','rejected') NOT NULL DEFAULT 'draft',
 tanggal_kirim DATETIME NULL,
 catatan_admin TEXT NULL,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 UNIQUE KEY uq_pendaftaran_user_tahun(user_id,tahun_ajaran),
 CONSTRAINT fk_pendaftaran_user FOREIGN KEY(user_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE CASCADE,
 CONSTRAINT fk_pendaftaran_jurusan FOREIGN KEY(jurusan_id) REFERENCES jurusan(id) ON UPDATE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS dokumen_pendaftaran (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 pendaftaran_id BIGINT UNSIGNED NOT NULL,
 jenis_dokumen ENUM('foto','kk','akta','ijazah') NOT NULL,
 nama_file_asli VARCHAR(255) NOT NULL,
 nama_file_simpan VARCHAR(255) NOT NULL,
 mime_type VARCHAR(100) NOT NULL,
 ukuran_file INT UNSIGNED NOT NULL,
 status_verifikasi ENUM('pending','valid','invalid') NOT NULL DEFAULT 'pending',
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 UNIQUE KEY uq_dokumen_jenis(pendaftaran_id,jenis_dokumen),
 CONSTRAINT fk_dokumen_pendaftaran FOREIGN KEY(pendaftaran_id) REFERENCES pendaftaran(id) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS riwayat_status (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 pendaftaran_id BIGINT UNSIGNED NOT NULL,
 status_lama VARCHAR(30) NULL,
 status_baru VARCHAR(30) NOT NULL,
 keterangan VARCHAR(255) NULL,
 diubah_oleh BIGINT UNSIGNED NULL,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 CONSTRAINT fk_riwayat_pendaftaran FOREIGN KEY(pendaftaran_id) REFERENCES pendaftaran(id) ON DELETE CASCADE,
 CONSTRAINT fk_riwayat_user FOREIGN KEY(diubah_oleh) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

INSERT IGNORE INTO jurusan(kode_jurusan,nama_jurusan,deskripsi,kuota) VALUES
('TKJ','Teknik Komputer dan Jaringan','Kompetensi jaringan komputer dan infrastruktur digital',36),
('RPL','Rekayasa Perangkat Lunak','Kompetensi pemrograman dan pengembangan perangkat lunak',36),
('AKL','Akuntansi dan Keuangan Lembaga','Kompetensi akuntansi dan pengelolaan keuangan',36),
('BDP','Bisnis Daring dan Pemasaran','Kompetensi pemasaran dan bisnis digital',36);
