<?php

declare(strict_types=1);

function start_session_if_needed(): void
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        $cookieParams = session_get_cookie_params();
        $secureRequest = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
            || (($_SERVER['SERVER_PORT'] ?? null) === '443');

        ini_set('session.use_strict_mode', '1');
        session_set_cookie_params(
            (int) $cookieParams['lifetime'],
            (string) $cookieParams['path'],
            (string) $cookieParams['domain'],
            (bool) $cookieParams['secure'] || $secureRequest,
            true
        );
        session_start();
    }
}

function base_url(): string
{
    return APP_URL;
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

function request_ip_address(): string
{
    $address = trim((string) ($_SERVER['REMOTE_ADDR'] ?? ''));

    return filter_var($address, FILTER_VALIDATE_IP) === false ? 'unknown' : $address;
}

function send_sensitive_page_headers(): void
{
    header('Cache-Control: no-store, max-age=0');
    header('Pragma: no-cache');
    header('Referrer-Policy: no-referrer');
    header('X-Robots-Tag: noindex, nofollow');
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
