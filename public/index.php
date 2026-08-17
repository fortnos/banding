<?php
declare(strict_types=1);
require dirname(__DIR__) . '/app/bootstrap.php';

Auth::requireLogin();
$pageTitle = 'Banding';

require ROOT_PATH . '/app/views/partials/header.php';
require ROOT_PATH . '/app/views/home.php';
require ROOT_PATH . '/app/views/partials/footer.php';
