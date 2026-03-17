<?php
require_once __DIR__ . '/src/load_env.php';

$host    = env('DB_HOST', '127.0.0.1');
$db_name = env('DB_NAME', 'faradj_db');
$db_user = env('DB_USER', 'root');
$db_pass = env('DB_PASS', '');

$charset = 'utf8mb4';
$dsn = "mysql:host=$host;dbname=$db_name;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
try {
    $pdo = new PDO($dsn, $db_user, $db_pass, $options);
} catch (PDOException $e) {
    http_response_code(503);
    echo json_encode(['success' => false, 'message' => 'DB error']);
    exit;
}

function db(): PDO {
    global $pdo;
    return $pdo;
}
