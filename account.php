<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';

$user = require_login();

render('account', [
    'pageTitle' => 'Account',
    'user' => $user,
]);
