<?php
/**
 * Migration: Add multilingual columns to events table
 * Run: php tools/migrate-events-multilang.php
 */
require __DIR__ . '/../db.php';

$columns = [
    'title_ru' => "ADD COLUMN title_ru varchar(255) DEFAULT NULL AFTER title",
    'title_en' => "ADD COLUMN title_en varchar(255) DEFAULT NULL AFTER title_ru",
    'excerpt_ru' => "ADD COLUMN excerpt_ru varchar(500) DEFAULT NULL AFTER excerpt",
    'excerpt_en' => "ADD COLUMN excerpt_en varchar(500) DEFAULT NULL AFTER excerpt_ru",
    'full_text_ru' => "ADD COLUMN full_text_ru text DEFAULT NULL AFTER full_text",
    'full_text_en' => "ADD COLUMN full_text_en text DEFAULT NULL AFTER full_text_ru",
];

foreach ($columns as $col => $sql) {
    try {
        $stmt = $pdo->query("SHOW COLUMNS FROM events LIKE '$col'");
        if ($stmt->rowCount() > 0) {
            echo "⏭ Column $col already exists\n";
            continue;
        }
        $pdo->exec("ALTER TABLE events $sql");
        echo "✅ Added column $col\n";
    } catch (PDOException $e) {
        echo "❌ $col: " . $e->getMessage() . "\n";
    }
}

echo "\nDone.\n";
