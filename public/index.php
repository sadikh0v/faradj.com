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

// Security Headers
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');
header('X-XSS-Protection: 1; mode=block');
header('Referrer-Policy: strict-origin-when-cross-origin');
header('Permissions-Policy: camera=(), microphone=(), geolocation=()');
if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
    header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
}

require_once __DIR__ . '/../src/load_env.php';
require_once __DIR__ . '/../src/helpers/i18n.php';

$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$path = rtrim($path, '/') ?: '/';

// Deploy
if ($path === '/deploy.php') {
    require __DIR__ . '/deploy.php';
    exit;
}

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

// Миграция (только для разработки)
if ($path === '/tools/migrate.php') {
    require __DIR__ . '/../tools/migrate.php';
    exit;
}

// Роутер
switch ($path) {
    case '/':
        $currentPage = 'index';
        $metaTitle = setting('seo_home_title') ?: 'Faradj MMC — Biznes və Yaradıcılıq üçün İlham';
        $metaDescription = setting('seo_home_desc') ?: 'DOMS rəsmi distribyutoru. Dəftərxana, ofis ləvazimatları, korporativ təchizat.';
        require __DIR__ . '/../src/views/header.php';
        require __DIR__ . '/../src/views/home.php';
        require __DIR__ . '/../src/views/footer.php';
        break;

    case '/events':
    case '/events.php':
        $currentPage = 'events';
        $metaTitle = setting('seo_events_title') ?: 'Xəbərlər — Faradj MMC';
        $metaDescription = setting('seo_events_desc') ?: 'Faradj MMC-nin son xəbərləri, tədbirləri və yeniliklərə baxın.';
        $extraCss = ['/assets/css/events.css'];
        $extraJs = ['/assets/js/events.js'];
        require __DIR__ . '/../db.php';
        require __DIR__ . '/../src/models/EventModel.php';
        $events = [];
        try {
            $eventModel = new EventModel($pdo);
            $events = $eventModel->getAll();
        } catch (PDOException $e) {
            $events = [];
        }
        require __DIR__ . '/../src/views/header.php';
        require __DIR__ . '/../src/views/events.php';
        require __DIR__ . '/../src/views/footer.php';
        break;

    case '/partners':
    case '/partners.php':
        $currentPage = 'partners';
        $metaTitle = setting('seo_partners_title') ?: 'Tərəfdaşlar — Faradj MMC';
        $metaDescription = setting('seo_partners_desc') ?: 'Faradj MMC-nin brendləri və korporativ müştəriləri.';
        $extraCss = ['/assets/css/partners.css'];
        $extraJs = ['/assets/js/partners.js'];
        require __DIR__ . '/../db.php';
        require __DIR__ . '/../src/views/header.php';
        require __DIR__ . '/../src/views/partners.php';
        require __DIR__ . '/../src/views/footer.php';
        break;

    case '/contacts':
    case '/contacts.php':
        $currentPage = 'contacts';
        $metaTitle = setting('seo_contacts_title') ?: 'Əlaqə — Faradj MMC';
        $metaDescription = setting('seo_contacts_desc') ?: (setting('phone_main', '+994 55 859 12 11') . ' | ' . setting('email_info', 'info@faradj.com') . ' | Bakı');
        $extraCss = ['/assets/css/contacts.css'];
        $extraJs = ['/assets/js/contacts.js'];
        require __DIR__ . '/../src/views/header.php';
        require __DIR__ . '/../src/views/contacts.php';
        require __DIR__ . '/../src/views/footer.php';
        break;

    case '/b2b':
    case '/b2b.php':
        $currentPage = 'b2b';
        $metaTitle = setting('seo_b2b_title') ?: 'B2B Müraciət — Faradj MMC';
        $metaDescription = setting('seo_b2b_desc') ?: 'Korporativ əməkdaşlıq üçün müraciət forması. Xüsusi qiymətlər və şərtlər.';
        $extraCss = ['/assets/css/b2b.css'];
        $extraJs = ['/assets/js/b2b.js'];
        require __DIR__ . '/../src/views/header.php';
        require __DIR__ . '/../src/views/b2b.php';
        require __DIR__ . '/../src/views/footer.php';
        break;

    case '/catalog':
        header('Location: https://catalog.faradj.com', true, 301);
        exit;

    case '/privacy':
    case '/privacy.php':
        $currentPage = 'privacy';
        $metaTitle = 'Məxfilik Siyasəti — Faradj MMC';
        require __DIR__ . '/../src/views/header.php';
        require __DIR__ . '/../src/views/privacy.php';
        require __DIR__ . '/../src/views/footer.php';
        break;

    case '/thank-you':
    case '/thank-you.php':
        $currentPage = 'thank-you';
        $metaTitle = t('request.success') . ' — Faradj MMC';
        $metaDescription = t('request.success');
        require __DIR__ . '/../src/views/header.php';
        require __DIR__ . '/../src/views/thank-you.php';
        require __DIR__ . '/../src/views/footer.php';
        break;

    case '/sitemap.xml':
        require __DIR__ . '/sitemap.php';
        break;

    case '/health':
        echo 'OK';
        exit;

    case '/offline':
        require __DIR__ . '/offline.php';
        break;

    case '/api/counter':
    case '/api/counter.php':
        require __DIR__ . '/api/counter.php';
        break;

    case '/api/instagram':
        require __DIR__ . '/api/instagram.php';
        break;

    case '/contact-submit':
    case '/contact-submit.php':
        require __DIR__ . '/contact-submit.php';
        break;

    case '/b2b-submit':
    case '/b2b-submit.php':
        require __DIR__ . '/b2b-submit.php';
        break;

    case '/callback':
    case '/callback.php':
        require __DIR__ . '/callback.php';
        break;

    default:
        http_response_code(404);
        $currentPage = '404';
        $metaTitle = '404 — Səhifə tapılmadı';
        $extraCss = ['/assets/css/404.css'];
        $extraJs = ['/assets/js/404.js'];
        require __DIR__ . '/../src/views/header.php';
        require __DIR__ . '/../src/views/404.php';
        require __DIR__ . '/../src/views/footer.php';
        break;
}
