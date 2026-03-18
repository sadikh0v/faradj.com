<?php
header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false]);
    exit;
}
require_once __DIR__ . '/../src/load_env.php';
require_once __DIR__ . '/../src/helpers/RateLimit.php';

if (!csrf_verify()) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}
if (!RateLimit::check('b2b', 3, 600)) {
    http_response_code(429);
    echo json_encode(['success' => false, 'message' => 'Çox sayda müraciət. 10 dəqiqə sonra yenidən cəhd edin.']);
    exit;
}
$data = [
    'company'  => trim($_POST['company'] ?? ''),
    'contact'  => trim($_POST['contact'] ?? ''),
    'phone'    => trim($_POST['phone'] ?? ''),
    'email'    => trim($_POST['email'] ?? ''),
    'activity' => trim($_POST['activity'] ?? ''),
    'volume'   => trim($_POST['volume'] ?? ''),
    'budget'   => trim($_POST['budget'] ?? ''),
    'products' => trim($_POST['products'] ?? ''),
    'note'     => trim($_POST['note'] ?? ''),
];
if (!$data['company'] || !$data['contact'] || !$data['phone'] || !$data['email']) {
    echo json_encode(['success' => false, 'message' => 'Zəruri sahələr doldurulmalıdır']);
    exit;
}
try {
    require_once __DIR__ . '/../db.php';
    $stmt = $pdo->prepare("
        INSERT INTO b2b_requests (company, contact, phone, email, activity, volume, budget, products, note)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([
        $data['company'], $data['contact'], $data['phone'], $data['email'],
        $data['activity'] ?: null, $data['volume'] ?: null, $data['budget'] ?: null,
        $data['products'] ?: null, $data['note'] ?: null
    ]);
    $pdo->prepare("INSERT INTO site_actions (action_type) VALUES (?)")->execute(['form_submit']);
    require_once __DIR__ . '/../src/helpers/Mailer.php';
    $mailData = [
        'company'  => $data['company'],
        'contact'  => $data['contact'],
        'phone'    => $data['phone'],
        'email'    => $data['email'],
        'activity' => $data['activity'],
        'volume'   => $data['volume'],
        'budget'   => $data['budget'],
        'products' => $data['products'],
        'note'     => $data['note'],
    ];
    $sent = Mailer::sendB2B($mailData);
    if (!$sent) {
        error_log('[B2B] Mail failed but data saved');
    }
    Mailer::sendAdminNotification([
        'type' => 'b2b',
        'company' => $data['company'],
        'contact' => $data['contact'],
        'phone' => $data['phone'],
        'email' => $data['email'],
        'activity' => $data['activity'],
        'volume' => $data['volume'],
        'budget' => $data['budget'],
        'products' => $data['products'],
        'note' => $data['note'],
        'admin_url' => 'https://faradj.com/admin/b2b',
    ]);
    echo json_encode(['success' => true]);
} catch (Throwable $e) {
    echo json_encode(['success' => false, 'message' => 'Xəta baş verdi']);
}
