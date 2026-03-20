<?php

declare(strict_types=1);

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/functions.php';

const REMEMBER_ME_COOKIE = 'wyt_remember';
const REMEMBER_ME_DAYS = 3650;
const PASSWORD_RESET_HOURS = 1;

function current_user(): ?array
{
    start_session_if_needed();
    static $user = null;

    if (!empty($_SESSION['user_id'])) {
        if ($user !== null && (int) $user['id'] === (int) $_SESSION['user_id']) {
            return $user;
        }

        $user = find_user_by_id((int) $_SESSION['user_id']);

        if ($user !== null) {
            return $user;
        }

        unset($_SESSION['user_id']);
    }

    $user = login_user_from_remember_cookie();

    if ($user !== null) {
        return $user;
    }

    return null;
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
    issue_remember_me_cookie((int) $user['id']);
}

function logout_user(): void
{
    clear_remember_me_cookie();
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

function find_user_by_id(int $id): ?array
{
    $statement = db()->prepare('SELECT id, email, password_hash, created_at FROM users WHERE id = :id');
    $statement->execute(['id' => $id]);

    return $statement->fetch() ?: null;
}

function find_user_by_email(string $email): ?array
{
    $statement = db()->prepare('SELECT * FROM users WHERE email = :email');
    $statement->execute(['email' => $email]);

    return $statement->fetch() ?: null;
}

function update_user_email(int $userId, string $email): void
{
    $statement = db()->prepare('UPDATE users SET email = :email WHERE id = :id');
    $statement->execute([
        'email' => $email,
        'id' => $userId,
    ]);
}

function update_user_password(int $userId, string $password): void
{
    $statement = db()->prepare('UPDATE users SET password_hash = :password_hash WHERE id = :id');
    $statement->execute([
        'password_hash' => password_hash($password, PASSWORD_DEFAULT),
        'id' => $userId,
    ]);
}

function delete_password_reset_tokens_for_user(int $userId): void
{
    $statement = db()->prepare('DELETE FROM password_reset_tokens WHERE user_id = :user_id');
    $statement->execute(['user_id' => $userId]);
}

function create_password_reset_token(int $userId): string
{
    delete_password_reset_tokens_for_user($userId);

    $token = bin2hex(random_bytes(32));
    $statement = db()->prepare(
        'INSERT INTO password_reset_tokens (user_id, token_hash, expires_at, created_at) VALUES (:user_id, :token_hash, :expires_at, NOW())'
    );
    $statement->execute([
        'user_id' => $userId,
        'token_hash' => hash('sha256', $token),
        'expires_at' => gmdate('Y-m-d H:i:s', time() + (PASSWORD_RESET_HOURS * 3600)),
    ]);

    return $token;
}

function find_password_reset_request(string $token): ?array
{
    $statement = db()->prepare(
        'SELECT password_reset_tokens.id, password_reset_tokens.user_id, password_reset_tokens.expires_at, users.email
         FROM password_reset_tokens
         INNER JOIN users ON users.id = password_reset_tokens.user_id
         WHERE token_hash = :token_hash'
    );
    $statement->execute([
        'token_hash' => hash('sha256', $token),
    ]);

    $request = $statement->fetch() ?: null;

    if ($request === null) {
        return null;
    }

    if (strtotime((string) $request['expires_at']) < time()) {
        delete_password_reset_token((int) $request['id']);
        return null;
    }

    return $request;
}

function delete_password_reset_token(int $tokenId): void
{
    $statement = db()->prepare('DELETE FROM password_reset_tokens WHERE id = :id');
    $statement->execute(['id' => $tokenId]);
}

function password_reset_url(string $token): string
{
    return base_url() . '/reset-password.php?token=' . urlencode($token);
}

function issue_remember_me_cookie(int $userId): void
{
    clear_existing_remember_tokens($userId);

    $selector = bin2hex(random_bytes(12));
    $validator = bin2hex(random_bytes(32));

    $statement = db()->prepare(
        'INSERT INTO user_remember_tokens (user_id, selector, token_hash, expires_at, created_at)
         VALUES (:user_id, :selector, :token_hash, :expires_at, NOW())'
    );
    $statement->execute([
        'user_id' => $userId,
        'selector' => $selector,
        'token_hash' => hash('sha256', $validator),
        'expires_at' => gmdate('Y-m-d H:i:s', time() + (REMEMBER_ME_DAYS * 86400)),
    ]);

    setcookie(
        REMEMBER_ME_COOKIE,
        $selector . ':' . $validator,
        time() + (REMEMBER_ME_DAYS * 86400),
        remember_cookie_path(),
        '',
        is_https_request(),
        true
    );
}

function login_user_from_remember_cookie(): ?array
{
    if (empty($_COOKIE[REMEMBER_ME_COOKIE]) || !is_string($_COOKIE[REMEMBER_ME_COOKIE])) {
        return null;
    }

    $parts = explode(':', $_COOKIE[REMEMBER_ME_COOKIE], 2);

    if (count($parts) !== 2) {
        clear_remember_me_cookie();
        return null;
    }

    $selector = $parts[0];
    $validator = $parts[1];

    $statement = db()->prepare(
        'SELECT id, user_id, token_hash, expires_at
         FROM user_remember_tokens
         WHERE selector = :selector'
    );
    $statement->execute(['selector' => $selector]);
    $tokenRow = $statement->fetch() ?: null;

    if ($tokenRow === null || strtotime((string) $tokenRow['expires_at']) < time()) {
        if ($tokenRow !== null) {
            delete_remember_token((int) $tokenRow['id']);
        }

        clear_remember_me_cookie();
        return null;
    }

    if (!hash_equals((string) $tokenRow['token_hash'], hash('sha256', $validator))) {
        delete_remember_token((int) $tokenRow['id']);
        clear_remember_me_cookie();
        return null;
    }

    $user = find_user_by_id((int) $tokenRow['user_id']);

    if ($user === null) {
        delete_remember_token((int) $tokenRow['id']);
        clear_remember_me_cookie();
        return null;
    }

    login_user($user);

    return $user;
}

function clear_existing_remember_tokens(int $userId): void
{
    $statement = db()->prepare('DELETE FROM user_remember_tokens WHERE user_id = :user_id');
    $statement->execute(['user_id' => $userId]);
}

function delete_remember_token(int $tokenId): void
{
    $statement = db()->prepare('DELETE FROM user_remember_tokens WHERE id = :id');
    $statement->execute(['id' => $tokenId]);
}

function clear_remember_me_cookie(): void
{
    if (!empty($_COOKIE[REMEMBER_ME_COOKIE])) {
        $parts = explode(':', (string) $_COOKIE[REMEMBER_ME_COOKIE], 2);

        if (!empty($parts[0])) {
            $statement = db()->prepare('DELETE FROM user_remember_tokens WHERE selector = :selector');
            $statement->execute(['selector' => $parts[0]]);
        }
    }

    setcookie(
        REMEMBER_ME_COOKIE,
        '',
        time() - 42000,
        remember_cookie_path(),
        '',
        is_https_request(),
        true
    );

    unset($_COOKIE[REMEMBER_ME_COOKIE]);
}

function is_https_request(): bool
{
    return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || (($_SERVER['SERVER_PORT'] ?? null) === '443');
}

function remember_cookie_path(): string
{
    $path = rtrim(APP_BASE_PATH, '/');

    return $path === '' ? '/' : $path;
}
