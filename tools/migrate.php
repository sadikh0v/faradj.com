<?php
require_once __DIR__ . '/../src/load_env.php';
require_once __DIR__ . '/../db.php';

$queries = [

"CREATE TABLE IF NOT EXISTS users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(200) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin') DEFAULT 'admin',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)",

"CREATE TABLE IF NOT EXISTS events (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    title_ru VARCHAR(255),
    title_en VARCHAR(255),
    slug VARCHAR(255) UNIQUE,
    category VARCHAR(100),
    short_text TEXT,
    short_text_ru TEXT,
    short_text_en TEXT,
    full_text LONGTEXT,
    full_text_ru LONGTEXT,
    full_text_en LONGTEXT,
    image VARCHAR(500),
    event_date DATE,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)",

"CREATE TABLE IF NOT EXISTS contact_messages (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(200) NOT NULL,
    email VARCHAR(200) NOT NULL,
    phone VARCHAR(50) DEFAULT NULL,
    subject VARCHAR(255) DEFAULT NULL,
    message TEXT NOT NULL,
    is_read TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)",

"CREATE TABLE IF NOT EXISTS b2b_requests (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    company VARCHAR(255),
    contact VARCHAR(255),
    phone VARCHAR(100),
    email VARCHAR(200),
    activity VARCHAR(100),
    volume VARCHAR(100),
    budget VARCHAR(100),
    products TEXT,
    note TEXT,
    is_read TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)",

"CREATE TABLE IF NOT EXISTS callbacks (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(200),
    phone VARCHAR(100),
    time_pref VARCHAR(100),
    is_read TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)",

"CREATE TABLE IF NOT EXISTS visitors (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    ip_address VARCHAR(45),
    page VARCHAR(500),
    referrer VARCHAR(500),
    user_agent VARCHAR(500),
    lang VARCHAR(10),
    visited_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)",

"CREATE TABLE IF NOT EXISTS brands (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(200) NOT NULL,
    logo VARCHAR(500),
    website VARCHAR(500),
    badge VARCHAR(50),
    sort_order INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)",

"CREATE TABLE IF NOT EXISTS clients (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(200) NOT NULL,
    logo VARCHAR(500),
    badge VARCHAR(50),
    sort_order INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)",

"CREATE TABLE IF NOT EXISTS settings (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    key_name VARCHAR(100) NOT NULL UNIQUE,
    value TEXT,
    label VARCHAR(200),
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)",

// Добавить колонки если не существуют (MySQL 8.0.27+)
"ALTER TABLE contact_messages ADD COLUMN IF NOT EXISTS phone VARCHAR(50) DEFAULT NULL",
"ALTER TABLE contact_messages ADD COLUMN IF NOT EXISTS subject VARCHAR(255) DEFAULT NULL",
"ALTER TABLE contact_messages ADD COLUMN IF NOT EXISTS is_read TINYINT(1) DEFAULT 0",
"ALTER TABLE b2b_requests ADD COLUMN IF NOT EXISTS is_read TINYINT(1) DEFAULT 0",
"ALTER TABLE callbacks ADD COLUMN IF NOT EXISTS is_read TINYINT(1) DEFAULT 0",

// Начальные настройки
"INSERT IGNORE INTO settings (key_name, value, label) VALUES
    ('phone_main',    '+994 55 859 12 11',                    'Əsas telefon'),
    ('phone_second',  '+994 10 521 93 53',                    'İkinci telefon'),
    ('whatsapp',      '994506167212',                         'WhatsApp nömrəsi'),
    ('email_info',    'info@faradj.com',                      'Əlaqə email'),
    ('email_sales',   'sales@faradj.org',                     'Satış email'),
    ('address_main',  'Bakı, İnşaatçılar pr. 106',            'Əsas ünvan'),
    ('address_store', 'Bakı, Murtuza Muxtarov 179',           'Mağaza ünvanı'),
    ('instagram',     'https://www.instagram.com/qelemstationery', 'Instagram'),
    ('tiktok',        'https://www.tiktok.com/@qelemstationery',   'TikTok'),
    ('linkedin',      'https://www.linkedin.com/in/faradjmmc',     'LinkedIn')
",
];

$success = 0;
$errors = [];

foreach ($queries as $sql) {
    try {
        $pdo->exec($sql);
        $success++;
    } catch (PDOException $e) {
        $errors[] = $e->getMessage();
    }
}

echo "<h3>✅ Uğurlu: $success</h3>";
if ($errors) {
    echo "<h3>⚠️ Xətalar:</h3><ul>";
    foreach ($errors as $e) echo "<li>" . htmlspecialchars($e) . "</li>";
    echo "</ul>";
}
echo "<p><strong>Migrate tamamlandı. Bu faylı silin!</strong></p>";
