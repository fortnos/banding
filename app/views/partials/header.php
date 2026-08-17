<?php $pageTitle = $pageTitle ?? 'Banding Gmail'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= e($pageTitle) ?> - <?= e(Config::get('APP_NAME', 'Gmail Banding')) ?></title>
<link rel="stylesheet" href="assets/css/app.css">
</head>
<body>
<header class="topbar">
  <div class="container topbar-inner">
    <span class="brand"><?= e(Config::get('APP_NAME', 'Gmail Banding')) ?></span>
    <nav class="nav">
      <a href="index.php" class="<?= $pageTitle === 'Banding' ? 'nav-active' : '' ?>">Banding</a>
      <a href="riwayat.php" class="<?= $pageTitle === 'Riwayat' ? 'nav-active' : '' ?>">Riwayat</a>
      <a href="logout.php" class="btn btn-ghost">Keluar</a>
    </nav>
  </div>
</header>
<main class="container">
