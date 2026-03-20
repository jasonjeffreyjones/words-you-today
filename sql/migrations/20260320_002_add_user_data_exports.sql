CREATE TABLE user_data_exports (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL UNIQUE,
    status ENUM('preparing', 'ready', 'failed') NOT NULL DEFAULT 'preparing',
    file_name VARCHAR(255) NOT NULL,
    file_path VARCHAR(1000) NOT NULL,
    generated_at DATETIME NULL,
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL,
    KEY idx_user_data_exports_status (status),
    CONSTRAINT fk_user_data_exports_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
