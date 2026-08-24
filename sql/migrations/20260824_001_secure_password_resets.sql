ALTER TABLE users
    ADD COLUMN auth_version INT UNSIGNED NOT NULL DEFAULT 1 AFTER password_hash;

CREATE TABLE password_reset_attempts (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    email_hash CHAR(64) NOT NULL,
    ip_hash CHAR(64) NOT NULL,
    requested_at DATETIME NOT NULL,
    KEY idx_password_reset_attempt_email (email_hash, requested_at),
    KEY idx_password_reset_attempt_ip (ip_hash, requested_at),
    KEY idx_password_reset_attempt_time (requested_at)
);

-- Tokens issued by the previous browser-visible flow must never remain usable.
DELETE FROM password_reset_tokens;
