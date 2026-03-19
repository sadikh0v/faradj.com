<?php
require_once __DIR__ . '/../src/load_env.php';
require_once __DIR__ . '/../db.php';
header('Content-Type: application/json; charset=utf-8');
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false]);
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
try {
    db()->prepare("INSERT INTO contact_messages (name, email, phone, subject, message) VALUES (?, ?, ?, ?, ?)")
        ->execute([$name, $email, $phone, $subject, $message]);
    echo json_encode(['success' => true, 'redirect' => '/thank-you?from=contact']);
} catch (\Throwable $e) {
    echo json_encode(['success' => false, 'error' => 'Xəta baş verdi']);
}
