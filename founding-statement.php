<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';

render('founding-statement', [
    'pageTitle' => 'Founding Statement for ' . APP_NAME,
]);
