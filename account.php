<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';

$user = require_login();

if (is_post_request()) {
    verify_csrf();

    $action = (string) ($_POST['action'] ?? '');

    if ($action === 'change_email') {
        $newEmail = trim((string) ($_POST['email'] ?? ''));

        if (!filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
            set_flash('danger', 'Please enter a valid email address.');
        } elseif (strcasecmp($newEmail, (string) $user['email']) !== 0 && find_user_by_email($newEmail) !== null) {
            set_flash('danger', 'That email address is already in use.');
        } else {
            update_user_email((int) $user['id'], $newEmail);
            set_flash('success', 'Your email address has been updated.');
            redirect('account.php');
        }
    }

    if ($action === 'change_password') {
        $currentPassword = (string) ($_POST['current_password'] ?? '');
        $newPassword = (string) ($_POST['new_password'] ?? '');
        $confirmPassword = (string) ($_POST['confirm_password'] ?? '');
        $freshUser = find_user_by_id((int) $user['id']);

        if ($freshUser === null || !password_verify($currentPassword, (string) $freshUser['password_hash'])) {
            set_flash('danger', 'Your current password was incorrect.');
        } elseif (strlen($newPassword) < 8) {
            set_flash('danger', 'New password must be at least 8 characters.');
        } elseif (!hash_equals($newPassword, $confirmPassword)) {
            set_flash('danger', 'New password and confirmation did not match.');
        } else {
            update_user_password((int) $user['id'], $newPassword);
            delete_password_reset_tokens_for_user((int) $user['id']);
            $updatedUser = find_user_by_id((int) $user['id']);

            if ($updatedUser !== null) {
                login_user($updatedUser);
            }

            set_flash('success', 'Your password has been updated.');
            redirect('account.php');
        }
    }
}

$user = require_login();

render('account', [
    'pageTitle' => 'Account',
    'user' => $user,
]);
