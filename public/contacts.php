<?php
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');
require_once __DIR__ . '/../src/load_env.php';
require_once __DIR__ . '/../src/helpers/i18n.php';
$currentPage = 'contacts';
$metaTitle = 'Əlaqə — Faradj MMC';
$metaDescription = '+994 55 859 12 11 | info@faradj.com | Bakı';
$extraCss = ['/assets/css/contacts.css'];
$extraJs = ['/assets/js/contacts.js'];
require __DIR__ . '/../src/views/header.php';
require __DIR__ . '/../src/views/contacts.php';
require __DIR__ . '/../src/views/footer.php';
