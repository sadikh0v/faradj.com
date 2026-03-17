<?php
http_response_code(404);
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
require_once __DIR__ . '/../src/load_env.php';
require_once __DIR__ . '/../src/helpers/i18n.php';
$currentPage = '404';
$metaTitle = '404 — Səhifə tapılmadı';
$extraCss = ['/assets/css/404.css'];
$extraJs = ['/assets/js/404.js'];
require __DIR__ . '/../src/views/header.php';
require __DIR__ . '/../src/views/404.php';
require __DIR__ . '/../src/views/footer.php';
