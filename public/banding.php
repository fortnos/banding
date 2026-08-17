<?php
declare(strict_types=1);
require dirname(__DIR__) . '/app/bootstrap.php';

Auth::requireLogin();

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit(json_encode(['error' => 'Method tidak diizinkan.']));
}

$action = $_POST['action'] ?? '';
$pdo = Database::connect();

if ($action === 'submit') {
    Csrf::requireValid();

    $emails = Validate::emails((string) ($_POST['emails'] ?? ''));
    if ($emails === []) {
        http_response_code(422);
        exit(json_encode(['error' => 'Tidak ada email valid yang dimasukkan.']));
    }

    $baseUrl = rtrim((string) Config::get('GOOGLE_APPEAL_URL', 'https://accounts.google.com/Login'), '/');
    $ip = $_SERVER['REMOTE_ADDR'] ?? null;

    $stmtEmail = $pdo->prepare('INSERT INTO emails (email) VALUES (?) ON DUPLICATE KEY UPDATE id = LAST_INSERT_ID(id)');
    $stmtAppeal = $pdo->prepare('INSERT INTO appeals (email_id, status, ip_address) VALUES (?, ?, ?)');

    $result = [];
    foreach ($emails as $email) {
        $stmtEmail->execute([$email]);
        $emailId = (int) $pdo->lastInsertId();
        $stmtAppeal->execute([$emailId, 'pending', $ip]);
        $appealId = (int) $pdo->lastInsertId();
        $result[] = [
            'id' => $appealId,
            'email' => $email,
            'url' => $baseUrl . '?' . http_build_query(['email' => $email]),
        ];
    }
    exit(json_encode(['emails' => $result]));
}

if ($action === 'status') {
    Csrf::requireValid();

    $id = (int) ($_POST['id'] ?? 0);
    $status = (string) ($_POST['status'] ?? '');
    if ($id <= 0 || !in_array($status, ['opened', 'failed'], true)) {
        http_response_code(422);
        exit(json_encode(['error' => 'Parameter tidak valid.']));
    }

    $stmt = $pdo->prepare('UPDATE appeals SET status = ?, opened_at = COALESCE(opened_at, NOW()) WHERE id = ?');
    $stmt->execute([$status, $id]);
    exit(json_encode(['ok' => true]));
}

http_response_code(422);
exit(json_encode(['error' => 'Aksi tidak dikenal.']));
