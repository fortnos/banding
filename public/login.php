<?php
declare(strict_types=1);
require dirname(__DIR__) . '/app/bootstrap.php';

if (Auth::check()) {
    header('Location: index.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    Csrf::requireValid();
    if (Auth::attempt(trim((string) ($_POST['username'] ?? '')), (string) ($_POST['password'] ?? ''))) {
        header('Location: index.php');
        exit;
    }
    $error = 'Username atau password salah.';
}

require ROOT_PATH . '/app/views/login.php';
