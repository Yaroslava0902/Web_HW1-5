<?php
/**
 * db_config.php
 * Налаштування підключення до MySQL бази даних на хостингу ukraine.com.ua
 */

define('DB_HOST',    'bezserv.mysql.ukraine.com.ua'); // сервер з phpMyAdmin
define('DB_USER',    'bezserv_baza16');               // користувач
define('DB_PASS',    'PASSWORD');                   // ← замініть на реальний пароль
define('DB_NAME',    'bezserv_baza16');               // назва БД
define('DB_PORT',    3306);
define('DB_CHARSET', 'utf8mb4');

/**
 * Створює і повертає PDO-підключення до БД
 */
function getDB(): PDO {
    static $pdo = null;
    if ($pdo !== null) return $pdo;

    $dsn = sprintf(
        'mysql:host=%s;port=%d;dbname=%s;charset=%s',
        DB_HOST, DB_PORT, DB_NAME, DB_CHARSET
    );
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
    return $pdo;
}

/**
 * Повертає відповідь у форматі JSON і завершує виконання
 */
function jsonResponse(array $data, int $status = 200): void {
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    header('Access-Control-Allow-Origin: *');
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit;
}