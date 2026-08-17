<?php
declare(strict_types=1);

define('ROOT_PATH', dirname(__DIR__));

require ROOT_PATH . '/app/config.php';
Config::load(ROOT_PATH . '/.env');

require ROOT_PATH . '/app/database.php';
require ROOT_PATH . '/app/auth.php';
require ROOT_PATH . '/app/csrf.php';
require ROOT_PATH . '/app/validate.php';

date_default_timezone_set(Config::get('APP_TIMEZONE', 'Asia/Jakarta') ?? 'Asia/Jakarta');

if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}
