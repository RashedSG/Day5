CREATE DATABASE IF NOT EXISTS assets_db DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE assets_db;
CREATE TABLE IF NOT EXISTS assets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    code VARCHAR(40) NOT NULL DEFAULT '',
    category VARCHAR(40) NOT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'in_use',
    location VARCHAR(80) NOT NULL DEFAULT '',
    purchase_date DATE NOT NULL,
    notes VARCHAR(500) NOT NULL DEFAULT '',
    created_at DATETIME NOT NULL,
    INDEX idx_category (category),
    INDEX idx_status (status),
    INDEX idx_name (name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
