<?php
// Отдавать статические файлы напрямую (для php -S)
if (php_sapi_name() === 'cli-server') {
    $file = __DIR__ . $_SERVER['REQUEST_URI'];
    $file = strtok($file, '?');
    if (is_file($file)) {
        return false;
    }
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

require_once __DIR__ . '/../src/load_env.php';
require_once __DIR__ . '/../src/helpers/i18n.php';

$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$path = rtrim($path, '/') ?: '/';

// /admin/* → admin/index.php (без проверки авторизации здесь)
if (str_starts_with($path, '/admin')) {
    $_GET['path'] = ltrim(substr($path, 6), '/') ?: '';
    require __DIR__ . '/admin/index.php';
    exit;
}

// Роут /lang
if ($path === '/lang') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $lang = $_POST['lang'] ?? 'az';
        if (!in_array($lang, ['az', 'ru', 'en'], true)) {
            $lang = 'az';
        }
        setcookie('lang', $lang, [
            'expires'  => time() + 365 * 24 * 3600,
            'path'     => '/',
            'httponly' => false,
            'samesite' => 'Lax',
        ]);

        $back = $_SERVER['HTTP_REFERER'] ?? '/';
        $back = preg_replace('/[?&]nc=\d+/', '', $back);
        $back = preg_replace('#^([^?]*)\&#', '$1?', $back);
        $sep = str_contains($back, '?') ? '&' : '?';
        $back = $back . $sep . 'nc=' . time();

        header('Location: ' . $back, true, 302);
        exit;
    }
    header('Location: /', true, 302);
    exit;
}

$currentPage = 'index';
$metaTitle = 'Faradj MMC — Biznes və Yaradıcılıq üçün İlham';
$metaDescription = 'DOMS rəsmi distribyutoru. Dəftərxana, ofis ləvazimatları, korporativ təchizat.';
require __DIR__ . '/../src/views/header.php';
require __DIR__ . '/../src/views/home.php';
require __DIR__ . '/../src/views/footer.php';
