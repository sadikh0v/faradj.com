<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
try {
    require_once __DIR__ . '/../../db.php';
    $count = (int) $pdo->query("
        SELECT COUNT(*) FROM site_actions
        WHERE created_at >= NOW() - INTERVAL 24 HOUR
    ")->fetchColumn();
    echo json_encode(['count' => $count]);
} catch (Throwable $e) {
    echo json_encode(['count' => 0]);
}
