<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/exports.php';

$user = require_login();
$export = find_user_export((int) $user['id']);

if (!user_export_is_downloadable($export)) {
    set_flash('warning', 'Your data export is not ready yet.');
    redirect('stats.php');
}

send_user_export_download($export);
