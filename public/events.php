<?php
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');
require_once __DIR__ . '/../src/load_env.php';
require_once __DIR__ . '/../src/helpers/i18n.php';
require __DIR__ . '/../db.php';
require __DIR__ . '/../src/models/EventModel.php';

$events = [];
try {
    $eventModel = new EventModel($pdo);
    $events = $eventModel->getAll();
} catch (PDOException $e) {
    $events = [];
}

$currentPage = 'events';
$metaTitle = 'Xəbərlər və Tədbirlər — Faradj MMC';
$metaDescription = 'Faradj MMC-nin son xəbərləri, tədbirləri və təqdimatları.';
$extraCss = ['/assets/css/events.css'];
$extraJs = ['/assets/js/events.js'];
require __DIR__ . '/../src/views/header.php';
require __DIR__ . '/../src/views/events.php';
require __DIR__ . '/../src/views/footer.php';
