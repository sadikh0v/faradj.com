<?php
header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}
$name = trim($_POST['name'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$time = trim($_POST['time'] ?? '');
if (!$name || !$phone) {
    echo json_encode(['success' => false, 'message' => 'Ad və telefon tələb olunur']);
    exit;
}
try {
    require_once __DIR__ . '/../db.php';
    $stmt = $pdo->prepare("INSERT INTO callbacks (name, phone, time_slot) VALUES (?, ?, ?)");
    $stmt->execute([$name, $phone, $time ?: null]);
    $pdo->prepare("INSERT INTO site_actions (action_type) VALUES (?)")->execute(['callback']);
    require_once __DIR__ . '/../src/helpers/Mailer.php';
    $sent = Mailer::sendCallback(['name' => $name, 'phone' => $phone, 'time' => $time]);
    if (!$sent) {
        error_log('[Callback] Mail failed but data saved');
    }
    echo json_encode(['success' => true]);
} catch (Throwable $e) {
    echo json_encode(['success' => false, 'message' => 'Xəta baş verdi']);
}
