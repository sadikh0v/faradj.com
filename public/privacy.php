<?php
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');
require_once __DIR__ . '/../src/load_env.php';
require_once __DIR__ . '/../src/helpers/i18n.php';
$currentPage = 'privacy';
$metaTitle = 'Məxfilik Siyasəti — Faradj MMC';
$metaDescription = 'Faradj MMC məxfilik siyasəti';
require __DIR__ . '/../src/views/header.php';
require __DIR__ . '/../src/views/privacy.php';
require __DIR__ . '/../src/views/footer.php';
