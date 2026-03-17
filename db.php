<?php
require_once __DIR__ . '/src/load_env.php';

// LOCAL
$host = '127.0.0.1';
$db_name = 'faradj_db';
$db_user = 'root';
$db_pass = '';

// PRODUCTION (раскомментировать при загрузке на Zomroo)
// $host = 'localhost';
// $db_name = 'u5755083_faradj_db';
// $db_user = 'u5755083_faradj_user_db';
// $db_pass = 'FRClm1lm2doms';

$charset = 'utf8mb4';
$dsn = "mysql:host=$host;dbname=$db_name;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try {
    $pdo = new PDO($dsn, $db_user, $db_pass, $options);
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'DB error'
    ]);
    exit;
}