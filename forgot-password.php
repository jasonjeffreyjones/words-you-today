<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';

send_sensitive_page_headers();

if (current_user() !== null) {
    redirect('account.php');
}

$email = '';

if (is_post_request()) {
    $requestStartedAt = microtime(true);
    verify_csrf();

    $email = trim((string) ($_POST['email'] ?? ''));

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        set_flash('danger', 'Please enter a valid email address.');
    } else {
        request_password_reset($email, request_ip_address());
        set_flash('info', 'If an account exists for that email, a password reset link has been sent.');
    }

    $minimumResponseSeconds = 1.0;
    $remainingSeconds = $minimumResponseSeconds - (microtime(true) - $requestStartedAt);
    if ($remainingSeconds > 0) {
        usleep((int) ($remainingSeconds * 1000000));
    }

    redirect('forgot-password.php');
}

render('forgot-password', [
    'pageTitle' => 'Forgot Password',
    'email' => $email,
]);
