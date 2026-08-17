<?php $pageTitle = 'Login'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login - <?= e(Config::get('APP_NAME', 'Gmail Banding')) ?></title>
<link rel="stylesheet" href="assets/css/app.css">
</head>
<body class="login-body">
<main class="login-card">
  <h1 class="login-title"><?= e(Config::get('APP_NAME', 'Gmail Banding')) ?></h1>
  <p class="muted login-sub">Masuk untuk mengelola banding Gmail.</p>
  <?php if (!empty($error)): ?>
    <div class="alert alert-error" role="alert"><?= e($error) ?></div>
  <?php endif; ?>
  <form method="post" action="login.php" class="form-stack">
    <?= Csrf::field() ?>
    <label for="username">Username</label>
    <input type="text" id="username" name="username" required autocomplete="username">
    <label for="password">Password</label>
    <input type="password" id="password" name="password" required autocomplete="current-password">
    <button type="submit" class="btn btn-primary btn-block">Masuk</button>
  </form>
</main>
</body>
</html>
