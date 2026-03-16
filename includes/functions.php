<?php

declare(strict_types=1);

function start_session_if_needed(): void
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
}

function h(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function current_app_date(): string
{
    return gmdate('Y-m-d');
}

function redirect(string $path): void
{
    header('Location: ' . app_url($path));
    exit;
}

function is_post_request(): bool
{
    return ($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST';
}

function set_flash(string $type, string $message): void
{
    start_session_if_needed();
    $_SESSION['flash'] = [
        'type' => $type,
        'message' => $message,
    ];
}

function get_flash(): ?array
{
    start_session_if_needed();

    if (!isset($_SESSION['flash'])) {
        return null;
    }

    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);

    return $flash;
}

function csrf_token(): string
{
    start_session_if_needed();

    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

function csrf_token_is_valid(?string $token): bool
{
    start_session_if_needed();

    if (!is_string($token)) {
        return false;
    }

    $sessionToken = $_SESSION['csrf_token'] ?? '';

    return is_string($sessionToken) && $sessionToken !== '' && hash_equals($sessionToken, $token);
}

function verify_csrf(): void
{
    if (!csrf_token_is_valid($_POST['csrf_token'] ?? null)) {
        http_response_code(400);
        exit('Invalid CSRF token.');
    }
}

function app_url(string $path = ''): string
{
    $basePath = rtrim(APP_BASE_PATH, '/');
    $trimmedPath = ltrim($path, '/');

    if ($trimmedPath === '') {
        return $basePath . '/';
    }

    return $basePath . '/' . $trimmedPath;
}

function render(string $template, array $data = []): void
{
    extract($data, EXTR_SKIP);
    require __DIR__ . '/../templates/header.php';
    require __DIR__ . '/../templates/' . $template . '.php';
    require __DIR__ . '/../templates/footer.php';
}
