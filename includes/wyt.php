<?php

declare(strict_types=1);

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/functions.php';

function find_next_signifier(int $userId, string $appDate): ?array
{
    $statement = db()->prepare(
        'SELECT s.id, s.text
         FROM signifiers s
         WHERE s.is_active = 1
           AND NOT EXISTS (
             SELECT 1
             FROM responses r
             WHERE r.user_id = :user_id
               AND r.signifier_id = s.id
               AND r.response_date = :response_date
           )
         ORDER BY RAND()
         LIMIT 1'
    );

    $statement->execute([
        'user_id' => $userId,
        'response_date' => $appDate,
    ]);

    return $statement->fetch() ?: null;
}

function record_response(int $userId, int $signifierId, int $answer, string $appDate): void
{
    $statement = db()->prepare(
        'INSERT INTO responses (user_id, signifier_id, response_date, answer, answered_at)
         VALUES (:user_id, :signifier_id, :response_date, :answer, NOW())
         ON DUPLICATE KEY UPDATE answer = VALUES(answer), answered_at = VALUES(answered_at)'
    );

    $statement->execute([
        'user_id' => $userId,
        'signifier_id' => $signifierId,
        'response_date' => $appDate,
        'answer' => $answer,
    ]);
}

function find_active_signifier_by_id(int $signifierId): ?array
{
    $statement = db()->prepare(
        'SELECT id, text
         FROM signifiers
         WHERE id = :id AND is_active = 1
         LIMIT 1'
    );

    $statement->execute(['id' => $signifierId]);

    return $statement->fetch() ?: null;
}

function fetch_user_stats(int $userId): array
{
    $statement = db()->prepare(
        'SELECT
            COUNT(*) AS total_responses,
            SUM(CASE WHEN answer = 1 THEN 1 ELSE 0 END) AS yes_count,
            SUM(CASE WHEN answer = 0 THEN 1 ELSE 0 END) AS no_count,
            COUNT(DISTINCT response_date) AS active_days,
            SUM(CASE WHEN response_date = :today THEN 1 ELSE 0 END) AS responses_today
         FROM responses
         WHERE user_id = :user_id'
    );

    $statement->execute([
        'today' => current_app_date(),
        'user_id' => $userId,
    ]);

    $stats = $statement->fetch() ?: [];
    $total = (int) ($stats['total_responses'] ?? 0);
    $yesCount = (int) ($stats['yes_count'] ?? 0);

    return [
        'total_responses' => $total,
        'yes_count' => $yesCount,
        'no_count' => (int) ($stats['no_count'] ?? 0),
        'active_days' => (int) ($stats['active_days'] ?? 0),
        'responses_today' => (int) ($stats['responses_today'] ?? 0),
        'yes_percentage' => $total > 0 ? round(($yesCount / $total) * 100, 1) : 0.0,
    ];
}
