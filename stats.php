<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/wyt.php';

$user = require_login();
$stats = fetch_user_stats((int) $user['id']);

render('stats', [
    'pageTitle' => 'My Stats',
    'stats' => $stats,
]);
