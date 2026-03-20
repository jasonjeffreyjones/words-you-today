<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';

if (current_user() !== null) {
    redirect('account.php');
}

$token = trim((string) ($_GET['token'] ?? $_POST['token'] ?? ''));
$resetRequest = $token === '' ? null : find_password_reset_request($token);

if (is_post_request()) {
    verify_csrf();

    $password = (string) ($_POST['password'] ?? '');
    $confirmPassword = (string) ($_POST['confirm_password'] ?? '');

    if ($resetRequest === null) {
        set_flash('danger', 'That password reset link is invalid or has expired.');
    } elseif (strlen($password) < 8) {
        set_flash('danger', 'Password must be at least 8 characters.');
    } elseif (!hash_equals($password, $confirmPassword)) {
        set_flash('danger', 'Password and confirmation did not match.');
    } else {
        update_user_password((int) $resetRequest['user_id'], $password);
        delete_password_reset_tokens_for_user((int) $resetRequest['user_id']);
        $user = find_user_by_id((int) $resetRequest['user_id']);

        if ($user !== null) {
            login_user($user);
        }

        set_flash('success', 'Your password has been reset.');
        redirect('account.php');
    }
}

render('reset-password', [
    'pageTitle' => 'Reset Password',
    'token' => $token,
    'resetRequest' => $resetRequest,
]);
