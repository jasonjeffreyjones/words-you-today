CREATE TABLE users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at DATETIME NOT NULL
);

CREATE TABLE signifiers (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    text VARCHAR(255) NOT NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_signifier_text (text)
);

CREATE TABLE responses (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    signifier_id INT UNSIGNED NOT NULL,
    response_date DATE NOT NULL,
    answer TINYINT(1) NOT NULL,
    answered_at DATETIME NOT NULL,
    UNIQUE KEY unique_user_signifier_day (user_id, signifier_id, response_date),
    KEY idx_user_date (user_id, response_date),
    KEY idx_signifier_date (signifier_id, response_date),
    CONSTRAINT fk_responses_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT fk_responses_signifier FOREIGN KEY (signifier_id) REFERENCES signifiers(id) ON DELETE CASCADE
);
