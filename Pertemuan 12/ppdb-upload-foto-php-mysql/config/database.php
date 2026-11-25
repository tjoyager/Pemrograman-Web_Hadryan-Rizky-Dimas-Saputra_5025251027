<?php
declare(strict_types=1);

/*
 * Konfigurasi koneksi database MySQL lokal dengan PDO
 */
const DB_HOST = 'localhost';
const DB_NAME = 'db_ppdb_smk';
const DB_USER = 'admin';
const DB_PASS = 'SandiKuat123';

function db(): PDO
{
    static $pdo = null;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';

    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ]);

    return $pdo;
}
