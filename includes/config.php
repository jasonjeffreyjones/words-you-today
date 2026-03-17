<?php

declare(strict_types=1);

define('APP_NAME', 'Words You Today');
define('APP_URL', 'https://jasonjones.ninja/words-you-today');
define('APP_BASE_PATH', '/words-you-today');
define('APP_TIMEZONE', 'UTC');

date_default_timezone_set(APP_TIMEZONE);

$externalConfig = dirname(__DIR__, 3) . '/wyt-config.php';

if (!is_file($externalConfig)) {
    http_response_code(500);
    exit('Missing external configuration file.');
}

require $externalConfig;

$requiredConstants = ['DB_HOST', 'DB_NAME', 'DB_USER', 'DB_PASS'];

foreach ($requiredConstants as $constantName) {
    if (!defined($constantName)) {
        http_response_code(500);
        exit('External configuration is incomplete.');
    }
}
