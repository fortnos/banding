<?php
declare(strict_types=1);
require dirname(__DIR__) . '/app/bootstrap.php';

$pdo = Database::connect();
$sql = file_get_contents(ROOT_PATH . '/database/schema.sql');
if ($sql === false) {
    fwrite(STDERR, "Gagal membaca database/schema.sql\n");
    exit(1);
}
$pdo->exec($sql);
echo "Migrasi selesai. Tabel: emails, appeals\n";
