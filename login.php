<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';

if (current_user() !== null) {
    redirect('wyt.php');
}

$email = '';

if (is_post_request()) {
    verify_csrf();

    $email = trim((string) ($_POST['email'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');
    $user = find_user_by_email($email);

    if ($user === null || !password_verify($password, $user['password_hash'])) {
        set_flash('danger', 'Invalid email or password.');
    } else {
        login_user($user);
        set_flash('success', 'Welcome back.');
        redirect('wyt.php');
    }
}

render('login', [
    'pageTitle' => 'Log In',
    'email' => $email,
]);
