<?php

declare(strict_types=1);

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/functions.php';

function current_user(): ?array
{
    start_session_if_needed();

    if (empty($_SESSION['user_id'])) {
        return null;
    }

    static $user = null;

    if ($user !== null && (int) $user['id'] === (int) $_SESSION['user_id']) {
        return $user;
    }

    $statement = db()->prepare('SELECT id, email, created_at FROM users WHERE id = :id');
    $statement->execute(['id' => (int) $_SESSION['user_id']]);
    $user = $statement->fetch() ?: null;

    return $user;
}

function require_login(): array
{
    $user = current_user();

    if ($user === null) {
        set_flash('warning', 'Please log in first.');
        redirect('login.php');
    }

    return $user;
}

function login_user(array $user): void
{
    start_session_if_needed();
    session_regenerate_id(true);
    $_SESSION['user_id'] = (int) $user['id'];
}

function logout_user(): void
{
    start_session_if_needed();
    $_SESSION = [];

    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], (bool) $params['secure'], (bool) $params['httponly']);
    }

    session_destroy();
}

function create_user(string $email, string $password): array
{
    $statement = db()->prepare(
        'INSERT INTO users (email, password_hash, created_at) VALUES (:email, :password_hash, NOW())'
    );

    $statement->execute([
        'email' => $email,
        'password_hash' => password_hash($password, PASSWORD_DEFAULT),
    ]);

    return [
        'id' => (int) db()->lastInsertId(),
        'email' => $email,
    ];
}

function find_user_by_email(string $email): ?array
{
    $statement = db()->prepare('SELECT * FROM users WHERE email = :email');
    $statement->execute(['email' => $email]);

    return $statement->fetch() ?: null;
}
