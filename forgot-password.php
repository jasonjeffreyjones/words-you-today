<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';

if (current_user() !== null) {
    redirect('account.php');
}

$email = '';
$resetLink = null;

if (is_post_request()) {
    verify_csrf();

    $email = trim((string) ($_POST['email'] ?? ''));

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        set_flash('danger', 'Please enter a valid email address.');
    } else {
        $user = find_user_by_email($email);

        if ($user !== null) {
            $token = create_password_reset_token((int) $user['id']);
            $resetLink = password_reset_url($token);
        }

        if ($resetLink === null) {
            set_flash('info', 'If that email exists in the system, a reset link is ready.');
        }
    }
}

render('forgot-password', [
    'pageTitle' => 'Forgot Password',
    'email' => $email,
    'resetLink' => $resetLink,
]);
