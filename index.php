<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';

$user = current_user();

render('home', [
    'pageTitle' => APP_NAME . ' by Dr. Jason Jeffrey Jones',
    'user' => $user,
]);
