<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/wyt.php';
require_once __DIR__ . '/includes/exports.php';

$user = require_login();

if (is_post_request()) {
    verify_csrf();

    $action = (string) ($_POST['action'] ?? '');

    if ($action === 'prepare_export') {
        try {
            prepare_user_export((int) $user['id']);
            set_flash('success', 'Your data export is ready to download.');
        } catch (Throwable $exception) {
            set_flash('danger', 'Unable to prepare your data export right now.');
        }

        redirect('stats.php');
    }
}

$stats = fetch_user_stats((int) $user['id']);
$dataExport = find_user_export((int) $user['id']);

render('stats', [
    'pageTitle' => 'My Stats',
    'stats' => $stats,
    'dataExport' => $dataExport,
]);
