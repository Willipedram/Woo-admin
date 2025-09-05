<?php
$host = 'localhost';
$db   = 'cpgparsi_TenderCPG40m';
$user = 'cpgparsi_pedramtender';
$pass = 'akLj&#HIq,?6WDx*';
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die('Database connection failed: ' . $conn->connect_error);
}
// Ensure proper UTF-8 encoding for Persian content
$conn->set_charset('utf8mb4');
$conn->query("SET NAMES 'utf8mb4' COLLATE 'utf8mb4_persian_ci'");
$conn->query("SET CHARACTER SET utf8mb4");
// Create tables if they do not exist
$conn->query("CREATE TABLE IF NOT EXISTS companies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    company_id BIGINT NOT NULL,
    company_name VARCHAR(255) NOT NULL,
    is_approved TINYINT(1) DEFAULT 0,
    download_link TEXT,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) CHARACTER SET utf8mb4 COLLATE utf8mb4_persian_ci");
$conn->query("ALTER TABLE companies CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_persian_ci");
$conn->query("CREATE TABLE IF NOT EXISTS settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    expert_name VARCHAR(255),
    expert_phone VARCHAR(50),
    download_link TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) CHARACTER SET utf8mb4 COLLATE utf8mb4_persian_ci");
$conn->query("ALTER TABLE settings CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_persian_ci");
?>