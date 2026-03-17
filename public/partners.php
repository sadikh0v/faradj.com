<?php
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');
require_once __DIR__ . '/../src/load_env.php';
require_once __DIR__ . '/../src/helpers/i18n.php';
$currentPage = 'partners';
$metaTitle = 'Tərəfdaşlar — Faradj MMC';
$metaDescription = 'Bizim brendlər və əməkdaşlıq etdiyimiz şirkətlər.';
$extraCss = ['/assets/css/partners.css'];
$extraJs = ['/assets/js/partners.js'];
require __DIR__ . '/../src/views/header.php';
require __DIR__ . '/../src/views/partners.php';
require __DIR__ . '/../src/views/footer.php';
