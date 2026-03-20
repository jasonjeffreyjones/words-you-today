<?php

declare(strict_types=1);

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/functions.php';

function find_user_export(int $userId): ?array
{
    $statement = db()->prepare(
        'SELECT id, user_id, status, file_name, file_path, generated_at, created_at, updated_at
         FROM user_data_exports
         WHERE user_id = :user_id
         LIMIT 1'
    );
    $statement->execute(['user_id' => $userId]);

    return $statement->fetch() ?: null;
}

function prepare_user_export(int $userId): array
{
    ensure_export_directory_exists();

    $fileName = export_file_name($userId);
    $filePath = export_file_path($fileName);
    $tempPath = $filePath . '.tmp';

    upsert_user_export_record($userId, 'preparing', $fileName, $filePath, null);

    $handle = fopen($tempPath, 'w');

    if ($handle === false) {
        upsert_user_export_record($userId, 'failed', $fileName, $filePath, null);
        throw new RuntimeException('Unable to open export file for writing.');
    }

    try {
        fputcsv($handle, ['answered_at', 'response_date', 'signifier_id', 'signifier', 'answer']);

        $statement = db()->prepare(
            'SELECT r.answered_at, r.response_date, r.signifier_id, s.text AS signifier_text, r.answer
             FROM responses r
             INNER JOIN signifiers s ON s.id = r.signifier_id
             WHERE r.user_id = :user_id
             ORDER BY r.answered_at ASC, r.id ASC'
        );
        $statement->execute(['user_id' => $userId]);

        while ($row = $statement->fetch()) {
            fputcsv($handle, [
                $row['answered_at'],
                $row['response_date'],
                $row['signifier_id'],
                $row['signifier_text'],
                ((int) $row['answer']) === 1 ? 'Yes' : 'No',
            ]);
        }
    } catch (Throwable $exception) {
        fclose($handle);
        @unlink($tempPath);
        upsert_user_export_record($userId, 'failed', $fileName, $filePath, null);
        throw $exception;
    }

    fclose($handle);

    if (!rename($tempPath, $filePath)) {
        @unlink($tempPath);
        upsert_user_export_record($userId, 'failed', $fileName, $filePath, null);
        throw new RuntimeException('Unable to finalize export file.');
    }

    $generatedAt = gmdate('Y-m-d H:i:s');
    upsert_user_export_record($userId, 'ready', $fileName, $filePath, $generatedAt);

    return find_user_export($userId);
}

function user_export_is_downloadable(?array $export): bool
{
    if ($export === null) {
        return false;
    }

    if (($export['status'] ?? '') !== 'ready') {
        return false;
    }

    $filePath = (string) ($export['file_path'] ?? '');

    return $filePath !== '' && is_file($filePath) && is_readable($filePath);
}

function send_user_export_download(array $export): void
{
    $filePath = (string) $export['file_path'];
    $fileName = basename((string) $export['file_name']);

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $fileName . '"');
    header('Content-Length: ' . (string) filesize($filePath));
    header('Cache-Control: private, max-age=0, must-revalidate');

    readfile($filePath);
    exit;
}

function export_directory_path(): string
{
    return rtrim(WYT_EXPORT_DIR, '/');
}

function export_file_name(int $userId): string
{
    return 'words-you-today-user-' . $userId . '-latest.csv';
}

function export_file_path(string $fileName): string
{
    return export_directory_path() . '/' . $fileName;
}

function ensure_export_directory_exists(): void
{
    $directory = export_directory_path();

    if (is_dir($directory)) {
        return;
    }

    if (!mkdir($directory, 0755, true) && !is_dir($directory)) {
        throw new RuntimeException('Unable to create export directory.');
    }
}

function upsert_user_export_record(int $userId, string $status, string $fileName, string $filePath, ?string $generatedAt): void
{
    $statement = db()->prepare(
        'INSERT INTO user_data_exports (user_id, status, file_name, file_path, generated_at, created_at, updated_at)
         VALUES (:user_id, :status, :file_name, :file_path, :generated_at, NOW(), NOW())
         ON DUPLICATE KEY UPDATE
            status = VALUES(status),
            file_name = VALUES(file_name),
            file_path = VALUES(file_path),
            generated_at = VALUES(generated_at),
            updated_at = NOW()'
    );
    $statement->execute([
        'user_id' => $userId,
        'status' => $status,
        'file_name' => $fileName,
        'file_path' => $filePath,
        'generated_at' => $generatedAt,
    ]);
}
