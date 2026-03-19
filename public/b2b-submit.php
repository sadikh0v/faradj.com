<?php
require_once __DIR__ . '/../src/load_env.php';
require_once __DIR__ . '/../db.php';

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false]);
    exit;
}

$company  = trim($_POST['company'] ?? '');
$contact  = trim($_POST['contact'] ?? '');
$phone    = trim($_POST['phone'] ?? '');
$email    = trim($_POST['email'] ?? '');
$activity = trim($_POST['activity'] ?? '');
$volume   = trim($_POST['volume'] ?? '');
$budget   = trim($_POST['budget'] ?? '');
$products = trim($_POST['products'] ?? '');
$note     = trim($_POST['note'] ?? '');

if (!$company || !$contact || !$phone) {
    echo json_encode(['success' => false, 'error' => 'Zəruri xanaları doldurun']);
    exit;
}

try {
    db()->prepare("
        INSERT INTO b2b_requests
        (company, contact, phone, email, activity, volume, budget, products, note)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
    ")->execute([$company, $contact, $phone, $email,
                 $activity, $volume, $budget, $products, $note]);
} catch (\Throwable $e) {
    error_log('[B2B] DB: ' . $e->getMessage());
    echo json_encode(['success' => false, 'error' => 'Xəta baş verdi']);
    exit;
}

echo json_encode(['success' => true, 'redirect' => '/thank-you?from=b2b']);

if (function_exists('fastcgi_finish_request')) {
    fastcgi_finish_request();
}

try {
    require_once __DIR__ . '/../src/helpers/Mailer.php';
    Mailer::sendB2B([
        'company' => $company, 'contact' => $contact,
        'phone' => $phone, 'email' => $email,
        'activity' => $activity, 'volume' => $volume,
        'budget' => $budget, 'products' => $products, 'note' => $note
    ]);
} catch (\Throwable $e) {
    error_log('[B2B] Mail: ' . $e->getMessage());
}
