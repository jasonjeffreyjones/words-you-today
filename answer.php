<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/wyt.php';

header('Content-Type: application/json');

if (!is_post_request()) {
    http_response_code(405);
    echo json_encode(['ok' => false, 'message' => 'Method not allowed.']);
    exit;
}

$user = current_user();

if ($user === null) {
    http_response_code(401);
    echo json_encode(['ok' => false, 'message' => 'Please log in again.']);
    exit;
}

if (!csrf_token_is_valid($_POST['csrf_token'] ?? null)) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'message' => 'Invalid CSRF token.']);
    exit;
}

$signifierId = filter_input(INPUT_POST, 'signifier_id', FILTER_VALIDATE_INT);
$answer = filter_input(INPUT_POST, 'answer', FILTER_VALIDATE_INT);

if ($signifierId === false || $signifierId === null || !in_array($answer, [0, 1], true)) {
    http_response_code(422);
    echo json_encode(['ok' => false, 'message' => 'Invalid signifier or answer.']);
    exit;
}

$signifier = find_active_signifier_by_id((int) $signifierId);

if ($signifier === null) {
    http_response_code(404);
    echo json_encode(['ok' => false, 'message' => 'That signifier is not available.']);
    exit;
}

$appDate = current_app_date();
record_response((int) $user['id'], (int) $signifierId, (int) $answer, $appDate);
$nextSignifier = find_next_signifier((int) $user['id'], $appDate);

if ($nextSignifier === null) {
    echo json_encode([
        'ok' => true,
        'done' => true,
    ]);
    exit;
}

echo json_encode([
    'ok' => true,
    'done' => false,
    'signifier' => [
        'id' => (int) $nextSignifier['id'],
        'text' => $nextSignifier['text'],
    ],
]);
