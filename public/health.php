<?php
header('Content-Type: application/json');

$checks = [];
$allOk = true;

try {
    require_once dirname(__DIR__) . '/db.php';
    $pdo->query('SELECT 1');
    $checks['database'] = 'ok';
} catch (Exception $e) {
    $checks['database'] = 'error';
    $allOk = false;
}

$checks['php'] = PHP_VERSION;
$checks['time'] = date('Y-m-d H:i:s');
$checks['status'] = $allOk ? 'healthy' : 'degraded';

http_response_code($allOk ? 200 : 503);
echo json_encode($checks, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
