<?php
session_start();
require __DIR__ . '/../db.php';
require __DIR__ . '/../src/config.php';
require __DIR__ . '/../src/models/EventModel.php';
require __DIR__ . '/../src/models/VisitorModel.php';

if (empty($_SESSION['admin'])) {
    header('Location: /admin/login');
    exit;
}

$eventModel = new EventModel($pdo);
$visitorModel = new VisitorModel($pdo);
$visitorTotal = $visitorModel->getTotalCount();
$visitorToday = $visitorModel->getTodayCount();
$visitorTopPages = $visitorModel->getTopPages(10);
$visitsByDay = $visitorModel->getVisitsByDay(7);

$b2bCount30 = 0;
$callbackCount30 = 0;
try {
    $b2bCount30 = (int) $pdo->query("SELECT COUNT(*) FROM b2b_requests WHERE created_at >= NOW() - INTERVAL 30 DAY")->fetchColumn();
} catch (PDOException $e) {}
try {
    $callbackCount30 = (int) $pdo->query("SELECT COUNT(*) FROM callbacks WHERE created_at >= NOW() - INTERVAL 30 DAY")->fetchColumn();
} catch (PDOException $e) {}
$message = '';
$messageType = 'success';

// Delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    if ($eventModel->delete($id)) {
        $_SESSION['admin_message'] = 'Silindi.';
    } else {
        $_SESSION['admin_message'] = 'Xəta baş verdi.';
        $_SESSION['admin_message_type'] = 'error';
    }
    header('Location: /admin.php');
    exit;
}

$message = $_SESSION['admin_message'] ?? '';
$messageType = $_SESSION['admin_message_type'] ?? 'success';
if ($message) {
    unset($_SESSION['admin_message'], $_SESSION['admin_message_type']);
}

// Create/Update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['title'])) {
    $data = [
        'title' => trim($_POST['title'] ?? ''),
        'excerpt' => trim($_POST['excerpt'] ?? ''),
        'full_text' => trim($_POST['full_text'] ?? ''),
        'category' => $_POST['category'] ?? 'xebərlər',
        'image_url' => trim($_POST['image_url'] ?? '') ?: null,
        'author' => trim($_POST['author'] ?? '') ?: null,
        'event_date' => trim($_POST['event_date'] ?? '') ?: null,
        'is_published' => isset($_POST['is_published']) ? 1 : 0,
    ];

    $id = (int)($_POST['id'] ?? 0);
    if ($id) {
        if ($eventModel->update($id, $data)) {
            $_SESSION['admin_message'] = 'Yeniləndi.';
        } else {
            $_SESSION['admin_message'] = 'Xəta baş verdi.';
            $_SESSION['admin_message_type'] = 'error';
        }
    } else {
        $eventModel->create($data);
        $_SESSION['admin_message'] = 'Əlavə edildi.';
    }
    header('Location: /admin.php');
    exit;
}

$events = $eventModel->getAllForAdmin();
$editEvent = null;

if (isset($_GET['edit'])) {
    $editEvent = $eventModel->getById((int)$_GET['edit']);
}

$currentPage = 'admin';
$pageTitle = 'Faradj MMC - Admin';
$extraCss = ['/assets/css/admin.css'];
$extraJs = ['/assets/js/admin.js'];
require __DIR__ . '/../src/views/header.php';
require __DIR__ . '/../src/views/admin.php';
require __DIR__ . '/../src/views/footer.php';
