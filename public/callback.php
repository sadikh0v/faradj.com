<?php
require_once __DIR__ . '/../src/load_env.php';
require_once __DIR__ . '/../db.php';
header('Content-Type: application/json; charset=utf-8');
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false]);
    exit;
}
$name      = trim($_POST['name'] ?? '');
$phone     = trim($_POST['phone'] ?? '');
$time_pref = trim($_POST['time_pref'] ?? '');
if (!$name || !$phone) {
    echo json_encode(['success' => false, 'error' => 'Ad və telefon tələb olunur']);
    exit;
}
try {
    db()->prepare("INSERT INTO callbacks (name, phone, time_pref) VALUES (?, ?, ?)")
        ->execute([$name, $phone, $time_pref]);
    echo json_encode(['success' => true, 'message' => 'Təşəkkür edirik! Tezliklə sizinlə əlaqə saxlayacağıq.']);
} catch (\Throwable $e) {
    echo json_encode(['success' => false, 'error' => 'Xəta baş verdi']);
}
