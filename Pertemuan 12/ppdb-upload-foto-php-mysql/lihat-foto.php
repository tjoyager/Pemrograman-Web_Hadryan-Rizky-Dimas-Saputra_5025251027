<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/bootstrap.php';
require_student();

$userId = (int) $_SESSION['user']['id'];
$documentId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($documentId && $documentId > 0) {
    $stmt = db()->prepare(
        'SELECT d.nama_file_simpan, d.mime_type
         FROM dokumen_pendaftaran d
         INNER JOIN pendaftaran p ON p.id = d.pendaftaran_id
         WHERE d.id = :id AND d.jenis_dokumen = "foto" AND p.user_id = :uid
         LIMIT 1'
    );
    $stmt->execute(['id' => $documentId, 'uid' => $userId]);
} else {
    // Jika parameter id tidak diberikan, ambil foto pendaftaran aktif milik siswa login
    $stmt = db()->prepare(
        'SELECT d.nama_file_simpan, d.mime_type
         FROM dokumen_pendaftaran d
         INNER JOIN pendaftaran p ON p.id = d.pendaftaran_id
         WHERE d.jenis_dokumen = "foto" AND p.user_id = :uid
         ORDER BY d.id DESC LIMIT 1'
    );
    $stmt->execute(['uid' => $userId]);
}
$document = $stmt->fetch();

if (!$document || !in_array($document['mime_type'], ['image/jpeg', 'image/png'], true)) {
    http_response_code(404);
    exit('Foto tidak ditemukan.');
}

$filename = basename((string)$document['nama_file_simpan']);
$path = __DIR__ . '/storage/dokumen/' . $filename;
if (!is_file($path)) {
    http_response_code(404);
    exit('File foto tidak tersedia.');
}

header('Content-Type: ' . $document['mime_type']);
header('Content-Length: ' . (string)filesize($path));
header('X-Content-Type-Options: nosniff');
header('Cache-Control: private, no-store, max-age=0');
header('Content-Disposition: inline; filename="foto-pendaftar"');
readfile($path);
exit;
