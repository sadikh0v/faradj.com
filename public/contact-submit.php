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
if (!RateLimit::check('contact', 5, 300)) {
    http_response_code(429);
    echo json_encode(['success' => false, 'message' => 'Çox sayda müraciət. 5 dəqiqə sonra yenidən cəhd edin.']);
    exit;
}
$data = [
    'name'    => trim($_POST['name'] ?? ''),
    'email'   => trim($_POST['email'] ?? ''),
    'phone'   => trim($_POST['phone'] ?? ''),
    'subject' => trim($_POST['subject'] ?? ''),
    'message' => trim($_POST['message'] ?? ''),
];
if (!$data['name'] || !$data['email'] || !$data['message']) {
    echo json_encode(['success' => false, 'message' => 'Ad, E-mail və Mesaj tələb olunur']);
    exit;
}
try {
    require_once __DIR__ . '/../db.php';
    require_once __DIR__ . '/../src/helpers/Mailer.php';

    $pdo->prepare("
        INSERT INTO contact_messages (name, email, phone, subject, message)
        VALUES (?, ?, ?, ?, ?)
    ")->execute([$data['name'], $data['email'], $data['phone'], $data['subject'], $data['message']]);

    $sent = Mailer::sendContact($data);
    Mailer::sendAdminNotification([
        'type' => 'contact',
        'name' => $data['name'],
        'email' => $data['email'],
        'phone' => $data['phone'],
        'subject' => $data['subject'],
        'message' => $data['message'],
        'admin_url' => 'https://faradj.com/admin/contacts',
    ]);
    if (!$sent) {
        error_log('[Contact] Mail failed but data saved');
    }
    echo json_encode(['success' => true]);
} catch (Throwable $e) {
    echo json_encode(['success' => false, 'message' => 'Xəta baş verdi']);
}
