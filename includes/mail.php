<?php

declare(strict_types=1);

require_once __DIR__ . '/config.php';

const WYT_SMTP_READ_BYTES = 8192;
const WYT_SMTP_MAX_RESPONSE_BYTES = 65536;

function wyt_mail_send_password_reset(string $toAddress, string $resetUrl): bool
{
    $subject = 'Reset your Words You Today password';
    $body = "A password reset was requested for your Words You Today account.\n\n"
        . "Use this secure link to choose a new password:\n"
        . $resetUrl . "\n\n"
        . "This link expires in one hour and can only be used once.\n\n"
        . "If you did not request this reset, you can ignore this email. "
        . "Your password has not been changed.\n";

    return wyt_mail_send_text($toAddress, $subject, $body);
}

function wyt_mail_send_password_changed(string $toAddress): bool
{
    $subject = 'Your Words You Today password was changed';
    $body = "The password for your Words You Today account was changed.\n\n"
        . "If you made this change, no further action is needed.\n\n"
        . "If you did not make this change, request another password reset "
        . "immediately at:\n"
        . APP_URL . "/forgot-password.php\n";

    return wyt_mail_send_text($toAddress, $subject, $body);
}

function wyt_mail_send_text(string $toAddress, string $subject, string $body): bool
{
    try {
        $fromHeader = wyt_mail_required_config('WYT_MAIL_FROM');
        $replyToHeader = wyt_mail_optional_config('WYT_MAIL_REPLY_TO', $fromHeader);
        $returnPathHeader = wyt_mail_optional_config(
            'WYT_MAIL_RETURN_PATH',
            wyt_mail_address_from_header($fromHeader)
        );

        $from = wyt_mail_parse_address($fromHeader, 'WYT_MAIL_FROM');
        $replyTo = wyt_mail_parse_address($replyToHeader, 'WYT_MAIL_REPLY_TO');
        $returnPath = wyt_mail_parse_address($returnPathHeader, 'WYT_MAIL_RETURN_PATH');
        $to = wyt_mail_parse_address($toAddress, 'recipient email address');

        $message = wyt_mail_build_message($from, $replyTo, $to, $subject, $body);
        wyt_mail_send_smtp((string) $returnPath['address'], (string) $to['address'], $message);
        return true;
    } catch (RuntimeException $exception) {
        error_log('Words You Today SMTP mail error: ' . $exception->getMessage());
        return false;
    }
}

function wyt_mail_required_config(string $name): string
{
    if (!defined($name)) {
        throw new RuntimeException($name . ' is not configured.');
    }

    $value = trim((string) constant($name));
    if ($value === '') {
        throw new RuntimeException($name . ' cannot be empty.');
    }

    return $value;
}

function wyt_mail_optional_config(string $name, string $default): string
{
    if (!defined($name)) {
        return $default;
    }

    $value = trim((string) constant($name));
    return $value === '' ? $default : $value;
}

function wyt_mail_parse_address(string $headerAddress, string $label): array
{
    $headerAddress = trim($headerAddress);
    $name = '';
    $address = $headerAddress;

    if (preg_match('/^(.+)<([^<>]+)>$/', $headerAddress, $matches) === 1) {
        $name = trim((string) $matches[1], " \t\n\r\0\x0B\"'");
        $address = trim((string) $matches[2]);
    }

    if (filter_var($address, FILTER_VALIDATE_EMAIL) === false) {
        throw new RuntimeException($label . ' must contain a valid email address.');
    }

    return [
        'name' => $name,
        'address' => $address,
    ];
}

function wyt_mail_address_from_header(string $headerAddress): string
{
    $parsed = wyt_mail_parse_address($headerAddress, 'WYT_MAIL_FROM');
    return (string) $parsed['address'];
}

function wyt_mail_format_address(array $mailbox): string
{
    $address = (string) $mailbox['address'];
    $name = trim((string) $mailbox['name']);
    if ($name === '') {
        return $address;
    }

    $name = str_replace(["\\", '"', "\r", "\n"], ['\\\\', '\\"', '', ''], $name);
    return '"' . $name . '" <' . $address . '>';
}

function wyt_mail_sanitize_header_value(string $value, string $label): string
{
    if (preg_match('/[\r\n]/', $value) === 1) {
        throw new RuntimeException($label . ' cannot contain line breaks.');
    }

    return trim($value);
}

function wyt_mail_validate_hostname(string $host, string $label): string
{
    $host = strtolower(trim($host));
    if (preg_match('/\A(?=.{1,253}\z)[a-z0-9](?:[a-z0-9.-]*[a-z0-9])?\z/', $host) !== 1) {
        throw new RuntimeException($label . ' must be a valid hostname.');
    }

    return $host;
}

function wyt_mail_build_message(array $from, array $replyTo, array $to, string $subject, string $body): string
{
    $subject = wyt_mail_sanitize_header_value($subject, 'Email subject');
    if ($subject === '') {
        throw new RuntimeException('Email subject cannot be empty.');
    }

    $host = wyt_mail_optional_config('WYT_MAIL_MESSAGE_ID_DOMAIN', '');
    if ($host === '') {
        $domainStart = strrchr((string) $from['address'], '@');
        $host = $domainStart === false ? 'jasonjones.ninja' : substr($domainStart, 1);
    }
    $host = wyt_mail_validate_hostname((string) $host, 'WYT_MAIL_MESSAGE_ID_DOMAIN');

    $messageId = bin2hex(random_bytes(16)) . '@' . $host;
    $headers = [
        'Date: ' . date('r'),
        'From: ' . wyt_mail_format_address($from),
        'Reply-To: ' . wyt_mail_format_address($replyTo),
        'To: ' . wyt_mail_format_address($to),
        'Subject: ' . $subject,
        'Message-ID: <' . $messageId . '>',
        'MIME-Version: 1.0',
        'Content-Type: text/plain; charset=UTF-8',
        'Content-Transfer-Encoding: quoted-printable',
        'X-Mailer: Words You Today SMTP',
    ];

    return implode("\r\n", $headers) . "\r\n\r\n" . wyt_mail_prepare_body($body) . "\r\n";
}

function wyt_mail_prepare_body(string $body): string
{
    $body = str_replace(["\r\n", "\r"], "\n", $body);
    $body = quoted_printable_encode($body);
    $body = str_replace(["\r\n", "\r"], "\n", $body);
    $lines = explode("\n", $body);

    foreach ($lines as $index => $line) {
        if (substr($line, 0, 1) === '.') {
            $lines[$index] = '.' . $line;
        }
    }

    return implode("\r\n", $lines);
}

function wyt_mail_send_smtp(string $envelopeFrom, string $recipient, string $message): void
{
    $host = wyt_mail_validate_hostname(wyt_mail_required_config('WYT_SMTP_HOST'), 'WYT_SMTP_HOST');
    $port = (int) wyt_mail_optional_config('WYT_SMTP_PORT', '465');
    $encryption = strtolower(wyt_mail_optional_config('WYT_SMTP_ENCRYPTION', 'ssl'));
    $username = wyt_mail_required_config('WYT_SMTP_USERNAME');
    $password = wyt_mail_required_config('WYT_SMTP_PASSWORD');
    $timeout = (int) wyt_mail_optional_config('WYT_SMTP_TIMEOUT_SECONDS', '20');

    if ($port < 1 || $port > 65535) {
        throw new RuntimeException('WYT_SMTP_PORT must be between 1 and 65535.');
    }
    if ($timeout < 1 || $timeout > 60) {
        throw new RuntimeException('WYT_SMTP_TIMEOUT_SECONDS must be between 1 and 60.');
    }
    if ($encryption !== 'ssl' && $encryption !== 'tls' && $encryption !== 'starttls') {
        throw new RuntimeException('WYT_SMTP_ENCRYPTION must be ssl, tls, or starttls.');
    }

    $context = stream_context_create([
        'ssl' => [
            'verify_peer' => true,
            'verify_peer_name' => true,
            'allow_self_signed' => false,
            'peer_name' => $host,
            'SNI_enabled' => true,
        ],
    ]);

    $remote = $host . ':' . (string) $port;
    if ($encryption === 'ssl') {
        $remote = 'ssl://' . $remote;
    }

    $errno = 0;
    $errstr = '';
    $socket = @stream_socket_client(
        $remote,
        $errno,
        $errstr,
        $timeout,
        STREAM_CLIENT_CONNECT,
        $context
    );
    if ($socket === false) {
        throw new RuntimeException('Could not connect securely to the configured SMTP server.');
    }

    stream_set_timeout($socket, $timeout);
    try {
        wyt_mail_expect($socket, [220]);
        $ehloHost = wyt_mail_validate_hostname(
            wyt_mail_optional_config('WYT_SMTP_EHLO_HOST', 'jasonjones.ninja'),
            'WYT_SMTP_EHLO_HOST'
        );
        wyt_mail_command($socket, 'EHLO ' . $ehloHost, [250]);

        if ($encryption === 'tls' || $encryption === 'starttls') {
            wyt_mail_command($socket, 'STARTTLS', [220]);
            $cryptoEnabled = @stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT);
            if ($cryptoEnabled !== true) {
                throw new RuntimeException('Could not enable SMTP STARTTLS encryption.');
            }
            wyt_mail_command($socket, 'EHLO ' . $ehloHost, [250]);
        }

        wyt_mail_command($socket, 'AUTH LOGIN', [334]);
        wyt_mail_command($socket, base64_encode($username), [334]);
        wyt_mail_command($socket, base64_encode($password), [235]);
        wyt_mail_command($socket, 'MAIL FROM:<' . $envelopeFrom . '>', [250]);
        wyt_mail_command($socket, 'RCPT TO:<' . $recipient . '>', [250, 251]);
        wyt_mail_command($socket, 'DATA', [354]);
        wyt_mail_command($socket, $message . "\r\n.", [250]);

        try {
            wyt_mail_command($socket, 'QUIT', [221]);
        } catch (RuntimeException $exception) {
            // The server already accepted the message; QUIT is best-effort cleanup.
        }
    } finally {
        fclose($socket);
    }
}

function wyt_mail_command($socket, string $command, array $expectedCodes): string
{
    wyt_mail_write($socket, $command . "\r\n");
    return wyt_mail_expect($socket, $expectedCodes);
}

function wyt_mail_write($socket, string $data): void
{
    $bytesWritten = 0;
    $totalBytes = strlen($data);

    while ($bytesWritten < $totalBytes) {
        $result = @fwrite($socket, substr($data, $bytesWritten));
        if ($result === false || $result === 0) {
            throw new RuntimeException('Could not write to SMTP server.');
        }
        $bytesWritten += $result;
    }
}

function wyt_mail_expect($socket, array $expectedCodes): string
{
    $response = '';
    $code = 0;
    $complete = false;

    while (($line = @fgets($socket, WYT_SMTP_READ_BYTES)) !== false) {
        $response .= $line;
        if (strlen($response) > WYT_SMTP_MAX_RESPONSE_BYTES) {
            throw new RuntimeException('SMTP server response was too large.');
        }
        if (strlen($line) >= 3 && ctype_digit(substr($line, 0, 3))) {
            $code = (int) substr($line, 0, 3);
        }
        if (strlen($line) >= 4 && substr($line, 3, 1) === ' ') {
            $complete = true;
            break;
        }
    }

    if ($response === '') {
        throw new RuntimeException('SMTP server returned an empty response.');
    }
    if (!$complete) {
        throw new RuntimeException('SMTP server returned an incomplete response.');
    }
    if (!in_array($code, $expectedCodes, true)) {
        throw new RuntimeException('SMTP server returned unexpected status ' . (string) $code . '.');
    }

    return $response;
}
