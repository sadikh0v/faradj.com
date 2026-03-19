<?php
require_once __DIR__ . '/../src/load_env.php';
require_once __DIR__ . '/../src/helpers/i18n.php';
require_once __DIR__ . '/../src/helpers/csrf.php';
require_once __DIR__ . '/../db.php';

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    exit;
}

$name    = trim($_POST['name'] ?? '');
$email   = trim($_POST['email'] ?? '');
$phone   = trim($_POST['phone'] ?? '');
$subject = trim($_POST['subject'] ?? '');
$message = trim($_POST['message'] ?? '');

if (!$name || !$email || !$message) {
    echo json_encode(['success' => false, 'error' => 'Xanaları doldurun']);
    exit;
}

// Сохранить в БД
try {
    db()->prepare("
        INSERT INTO contact_messages (name, email, phone, subject, message)
        VALUES (?, ?, ?, ?, ?)
    ")->execute([$name, $email, $phone, $subject, $message]);
} catch (PDOException $e) {
    error_log('[Contact] DB error: ' . $e->getMessage());
    echo json_encode(['success' => false, 'error' => 'Xəta baş verdi']);
    exit;
}

// Email в фоне (не блокировать ответ)
try {
    require_once __DIR__ . '/../src/helpers/Mailer.php';
    Mailer::sendContact([
        'name' => $name, 'email' => $email,
        'phone' => $phone, 'subject' => $subject, 'message' => $message
    ]);
} catch (\Throwable $e) {
    error_log('[Contact] Mail error: ' . $e->getMessage());
}

echo json_encode(['success' => true, 'redirect' => '/thank-you?from=contact']);
