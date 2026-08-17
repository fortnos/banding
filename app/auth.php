<?php
declare(strict_types=1);

final class Auth
{
    public static function attempt(string $username, string $password): bool
    {
        $user = Config::get('ADMIN_USER', '');
        $pass = Config::get('ADMIN_PASS', '');
        if ($user === '' || $pass === '') {
            return false;
        }
        if (hash_equals($user, $username) && hash_equals($pass, $password)) {
            session_regenerate_id(true);
            $_SESSION['admin'] = true;
            return true;
        }
        return false;
    }

    public static function check(): bool
    {
        return ($_SESSION['admin'] ?? false) === true;
    }

    public static function requireLogin(): void
    {
        if (!self::check()) {
            header('Location: login.php');
            exit;
        }
    }

    public static function logout(): void
    {
        $_SESSION = [];
        session_destroy();
    }
}
