<?php

declare(strict_types=1);

define('DB_HOST', 'localhost');
define('DB_NAME', 'words_you_today');
define('DB_USER', 'replace_me');
define('DB_PASS', 'replace_me');
define('WYT_EXPORT_DIR', '/home/youraccount/wyt-exports');

define('WYT_MAIL_FROM', 'Words You Today <words-you-today@jasonjones.ninja>');
define('WYT_MAIL_REPLY_TO', 'words-you-today@jasonjones.ninja');
define('WYT_MAIL_RETURN_PATH', 'words-you-today@jasonjones.ninja');
define('WYT_MAIL_MESSAGE_ID_DOMAIN', 'jasonjones.ninja');

define('WYT_SMTP_HOST', 'jasonjones.ninja');
define('WYT_SMTP_PORT', 465);
define('WYT_SMTP_ENCRYPTION', 'ssl');
define('WYT_SMTP_USERNAME', 'words-you-today@jasonjones.ninja');
define('WYT_SMTP_PASSWORD', 'replace_me');
define('WYT_SMTP_TIMEOUT_SECONDS', 20);
define('WYT_SMTP_EHLO_HOST', 'jasonjones.ninja');
