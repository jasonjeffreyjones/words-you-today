<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';

send_sensitive_page_headers();

if (current_user() !== null) {
    redirect('account.php');
}

start_session_if_needed();

if (array_key_exists('token', $_GET)) {
    $token = trim((string) $_GET['token']);
    unset($_SESSION['password_reset_token_hash']);

    if (find_password_reset_request($token) !== null) {
        $_SESSION['password_reset_token_hash'] = hash('sha256', $token);
    }

    redirect('reset-password.php');
}

$tokenHash = (string) ($_SESSION['password_reset_token_hash'] ?? '');
$resetRequest = $tokenHash === '' ? null : find_password_reset_request_by_hash($tokenHash);

if ($resetRequest === null) {
    unset($_SESSION['password_reset_token_hash']);
}

if (is_post_request()) {
    verify_csrf();

    $password = (string) ($_POST['password'] ?? '');
    $confirmPassword = (string) ($_POST['confirm_password'] ?? '');

    if ($resetRequest === null) {
        set_flash('danger', 'That password reset link is invalid or has expired.');
        redirect('reset-password.php');
    } elseif (strlen($password) < 8) {
        set_flash('danger', 'Password must be at least 8 characters.');
    } elseif (!hash_equals($password, $confirmPassword)) {
        set_flash('danger', 'Password and confirmation did not match.');
    } else {
        $resetUser = reset_password_with_token_hash($tokenHash, $password);

        if ($resetUser === null) {
            unset($_SESSION['password_reset_token_hash']);
            set_flash('danger', 'That password reset link is invalid or has expired.');
            redirect('reset-password.php');
        } else {
            unset($_SESSION['password_reset_token_hash']);
            wyt_mail_send_password_changed((string) $resetUser['email']);
            set_flash('success', 'Your password has been reset. Please log in with your new password.');
            redirect('login.php');
        }
    }
}

render('reset-password', [
    'pageTitle' => 'Reset Password',
    'resetRequest' => $resetRequest,
]);
