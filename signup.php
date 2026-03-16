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

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        set_flash('danger', 'Please enter a valid email address.');
    } elseif (strlen($password) < 8) {
        set_flash('danger', 'Password must be at least 8 characters.');
    } elseif (find_user_by_email($email) !== null) {
        set_flash('danger', 'An account with that email already exists.');
    } else {
        $user = create_user($email, $password);
        login_user($user);
        set_flash('success', 'Your account has been created.');
        redirect('wyt.php');
    }
}

render('signup', [
    'pageTitle' => 'Sign Up',
    'email' => $email,
]);
