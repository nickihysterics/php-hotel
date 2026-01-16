<?php

declare(strict_types=1);

use App\Config\Env;
use App\Database;

require __DIR__ . '/autoload.php';

Env::load(__DIR__ . '/../.env');

if (session_status() !== PHP_SESSION_ACTIVE) {
    ini_set('session.cookie_httponly', '1');
    ini_set('session.use_strict_mode', '1');
    session_start();
}

$port = (int) Env::get('DB_PORT', '3306');

$database = new Database(
    host: Env::get('DB_HOST', '127.0.0.1'),
    port: $port,
    name: Env::get('DB_NAME', 'hotel'),
    user: Env::get('DB_USER', 'hotel'),
    pass: Env::get('DB_PASS', 'hotel')
);

$pdo = $database->pdo();
