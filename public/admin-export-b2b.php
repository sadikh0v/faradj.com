<?php
session_start();
require __DIR__ . '/../db.php';
if (empty($_SESSION['admin'])) {
    header('Location: /admin/login');
    exit;
}
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="b2b-requests.csv"');
$out = fopen('php://output', 'w');
fprintf($out, chr(0xEF) . chr(0xBB) . chr(0xBF));
try {
    $stmt = $pdo->query("SELECT * FROM b2b_requests ORDER BY created_at DESC");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (!empty($rows)) {
        fputcsv($out, array_keys($rows[0]));
        foreach ($rows as $row) {
            fputcsv($out, $row);
        }
    }
} catch (PDOException $e) {
    fputcsv($out, ['error' => 'No table']);
}
fclose($out);
exit;
