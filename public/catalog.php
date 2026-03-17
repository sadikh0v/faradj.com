<?php
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
require_once __DIR__ . '/../src/load_env.php';
require_once __DIR__ . '/../src/helpers/i18n.php';
$currentPage = 'catalog';
$metaTitle = 'Kataloq — Faradj MMC';
$metaDescription = 'Dəftərxana və ofis ləvazimatları kataloqu. DOMS və digər brendlər.';
$extraCss = ['/assets/css/kataloq.css'];
require __DIR__ . '/../src/views/header.php';
require __DIR__ . '/../src/views/catalog.php';
require __DIR__ . '/../src/views/footer.php';
