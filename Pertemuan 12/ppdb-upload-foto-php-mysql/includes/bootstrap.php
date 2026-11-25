<?php
declare(strict_types=1);
require_once __DIR__ . '/../config/database.php';

const PPDB_TAHUN = '2026/2027';

$isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_set_cookie_params(['lifetime'=>0,'path'=>'/','secure'=>$isHttps,'httponly'=>true,'samesite'=>'Lax']);
    session_start();
}
function e(?string $value): string { return htmlspecialchars($value ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
function redirect(string $path): never { header('Location: '.$path); exit; }
function csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) $_SESSION['csrf_token']=bin2hex(random_bytes(32));
    return $_SESSION['csrf_token'];
}
function verify_csrf(): bool {
    $token=$_POST['csrf_token']??'';
    return is_string($token)&&isset($_SESSION['csrf_token'])&&hash_equals($_SESSION['csrf_token'],$token);
}
function flash(string $key, ?string $message=null): ?string {
    if ($message!==null) { $_SESSION['_flash'][$key]=$message; return null; }
    $value=$_SESSION['_flash'][$key]??null; unset($_SESSION['_flash'][$key]);
    return is_string($value)?$value:null;
}
function require_student(): void {
    if (empty($_SESSION['user']) || ($_SESSION['user']['role']??'')!=='siswa') {
        flash('error','Silakan login sebagai calon siswa terlebih dahulu.');
        redirect('login.php');
    }
}
