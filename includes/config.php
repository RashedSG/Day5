<?php
declare(strict_types=1);

const DEBUG = true;

if (DEBUG) {
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
} else {
    ini_set('display_errors', '0');
    ini_set('log_errors', '1');
}
error_reporting(E_ALL);

define('BASE_PATH', dirname(__DIR__));
define('DATA_PATH', BASE_PATH . '/data');

const DB_DRIVER = 'sqlite';
const DB_SQLITE_FILE = DATA_PATH . '/assets.sqlite';

const DB_MYSQL = [
    'host'     => '127.0.0.1',
    'port'     => 3306,
    'database' => 'assets_db',
    'user'     => 'root',
    'password' => '',
];

const AUTH_USER = 'admin';
const AUTH_PASS_HASH = '$2y$10$JbdkY97y1dH1o1aamz.oBehHZJcB5APb86NnNuJWTjJCiiQZiYyS.';

const CATEGORIES = [
    'computers'  => 'أجهزة حاسوب',
    'furniture'  => 'أثاث',
    'vehicles'   => 'مركبات',
    'tools'      => 'عدد ومعدّات',
    'networking' => 'شبكات',
    'other'      => 'أخرى',
];

const STATUSES = [
    'in_use'      => 'قيد الاستخدام',
    'storage'     => 'في المخزن',
    'maintenance' => 'صيانة',
    'retired'     => 'مستبعد',
];

const PER_PAGE = 15;

if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'httponly' => true,
        'samesite' => 'Lax',
        'secure'   => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
    ]);
    session_start();
}

header('Content-Type: text/html; charset=UTF-8');
header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: strict-origin-when-cross-origin');
header('X-Frame-Options: SAMEORIGIN');

require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/auth.php';
