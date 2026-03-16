<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/wyt.php';

$user = require_login();
$appDate = current_app_date();
$signifier = find_next_signifier((int) $user['id'], $appDate);

render('wyt', [
    'pageTitle' => 'Words You Today',
    'appDate' => $appDate,
    'signifier' => $signifier,
]);
