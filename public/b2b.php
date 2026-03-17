<?php
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');
require_once __DIR__ . '/../src/load_env.php';
require_once __DIR__ . '/../src/helpers/i18n.php';
$currentPage = 'b2b';
$metaTitle = 'Müraciət — Faradj MMC';
$metaDescription = 'Korporativ əməkdaşlıq üçün müraciət göndərin.';
$extraCss = ['/assets/css/b2b.css'];
$extraJs = ['/assets/js/b2b.js'];
require __DIR__ . '/../src/views/header.php';
require __DIR__ . '/../src/views/b2b.php';
require __DIR__ . '/../src/views/footer.php';
