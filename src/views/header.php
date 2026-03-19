<?php
require_once __DIR__ . '/../load_env.php';
require_once __DIR__ . '/../helpers/i18n.php';
require_once __DIR__ . '/../helpers/img_webp.php';
$currentPage = $currentPage ?? 'index';
$isProduction = (function_exists('env') && env('APP_ENV') === 'production');
$assetSuffix = '';
$baseUrl = '/';
$currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$currentPath = rtrim($currentPath, '/') ?: '/';

// Visitor tracking (skip admin/auth)
if (!str_contains($currentPath, 'admin') && !str_contains($currentPath, 'auth')) {
    try {
        require_once __DIR__ . '/../../db.php';
        require_once __DIR__ . '/../models/VisitorModel.php';
        $visitorModel = new VisitorModel($pdo);
        $visitorModel->track();
    } catch (Throwable $e) {
        // Silently ignore
    }
}
$metaTitle = $metaTitle ?? 'Faradj MMC — Biznes və Yaradıcılıq üçün İlham';
$metaDesc = $metaDescription ?? 'Azərbaycanın aparıcı dəftərxana və ofis ləvazimatları təchizatçısı. DOMS rəsmi distribyutoru.';
?>
<!DOCTYPE html>
<html lang="<?= currentLang() ?>">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="<?= htmlspecialchars($metaDesc) ?>" />
    <meta name="keywords" content="dəftərxana, ofis ləvazimatları, DOMS, Faradj, Bakı, Azərbaycan" />
    <meta name="author" content="Faradj MMC" />

    <title><?= htmlspecialchars($metaTitle) ?></title>

    <!-- Open Graph -->
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Faradj MMC">
    <meta property="og:title" content="<?= htmlspecialchars($metaTitle ?? 'Faradj MMC') ?>">
    <meta property="og:description" content="<?= htmlspecialchars($metaDesc ?? 'DOMS rəsmi distribyutoru. Dəftərxana, ofis ləvazimatları, korporativ təchizat.') ?>">
    <meta property="og:url" content="https://faradj.com<?= htmlspecialchars($_SERVER['REQUEST_URI'] ?? '/') ?>">
    <meta property="og:image" content="https://faradj.com/assets/img/og-image.png">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:locale" content="<?= currentLang() === 'ru' ? 'ru_RU' : (currentLang() === 'en' ? 'en_US' : 'az_AZ') ?>">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= htmlspecialchars($metaTitle ?? 'Faradj MMC') ?>">
    <meta name="twitter:description" content="<?= htmlspecialchars($metaDesc ?? 'DOMS rəsmi distribyutoru. Dəftərxana, ofis ləvazimatları, korporativ təchizat.') ?>">
    <meta name="twitter:image" content="https://faradj.com/assets/img/og-image.png">

    <!-- Canonical -->
    <link rel="canonical" href="https://faradj.com<?= htmlspecialchars(strtok($_SERVER['REQUEST_URI'] ?? '/', '?')) ?>">

    <?php
    $baseUrlHref = 'https://faradj.com' . parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
    $baseUrlHref = rtrim($baseUrlHref, '/') ?: 'https://faradj.com/';
    ?>
    <link rel="alternate" hreflang="az" href="<?= htmlspecialchars($baseUrlHref) ?>">
    <link rel="alternate" hreflang="ru" href="<?= htmlspecialchars($baseUrlHref) ?>">
    <link rel="alternate" hreflang="en" href="<?= htmlspecialchars($baseUrlHref) ?>">
    <link rel="alternate" hreflang="x-default" href="<?= htmlspecialchars($baseUrlHref) ?>">

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700;800&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <?php if (($currentPage ?? '') === 'partners'): ?>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    <?php endif; ?>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/favicon.ico?v=8" />
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png?v=8" />
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png?v=8" />
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png?v=8" />
    <link rel="icon" type="image/png" sizes="192x192" href="/favicon-192x192.png?v=8" />
    <meta name="msapplication-TileImage" content="/favicon-192x192.png?v=8" />
    <meta name="msapplication-TileColor" content="#6c63ff" />

    <link rel="manifest" href="/manifest.json" />
    <meta name="theme-color" content="#6c63ff" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="default" />
    <meta name="apple-mobile-web-app-title" content="Faradj MMC" />

    <link rel="stylesheet" href="/assets/css/style<?= $assetSuffix ?>.css" />
    <link rel="stylesheet" href="/assets/css/tablet<?= $assetSuffix ?>.css" />
    <?php if (!empty($extraCss)): ?>
        <?php foreach ((array)$extraCss as $css): ?>
    <link rel="stylesheet" href="<?= htmlspecialchars(preg_replace('/\.(css|js)$/', $assetSuffix . '.$1', $css)) ?>" />
        <?php endforeach; ?>
    <?php endif; ?>
    <link rel="stylesheet" href="/assets/css/mobile<?= $assetSuffix ?>.css" media="(max-width: 767px)" />
    <?php if ($isProduction): ?>
    <script>
    window._faradjAnalytics = {
      gaId: <?= json_encode(env('GA_MEASUREMENT_ID') ?? '') ?>,
      ymId: <?= (int)(env('YANDEX_COUNTER_ID') ?? 0) ?>,
      hjId: <?= json_encode(env('HOTJAR_ID') ?? '') ?>
    };
    </script>
    <?php endif; ?>
</head>
<body>

    <div id="scrollProgress" class="scroll-progress"></div>

    <div id="parallax-scene" class="parallax-container">
        <div class="floating-wrapper" style="top: 60%; left: 80%">
            <?php img_webp('/assets/img/background/notebook.png', 'Notebook', ['class' => 'floating-item']); ?>
        </div>
        <div class="floating-wrapper" style="top: 80%; left: 15%">
            <?php img_webp('/assets/img/background/pencil.png', 'Pencil', ['class' => 'floating-item', 'style' => 'width: 20px; height: auto']); ?>
        </div>
        <div class="floating-wrapper" style="top: 25%; left: 85%">
            <?php img_webp('/assets/img/background/eraser.png', 'Eraser', ['class' => 'floating-item']); ?>
        </div>
        <div class="floating-wrapper" style="top: 15%; left: 10%">
            <?php img_webp('/assets/img/background/pen.png', 'Pen', ['class' => 'floating-item']); ?>
        </div>
        <div class="floating-wrapper layer-deep" style="top: 5%; left: 45%">
            <?php img_webp('/assets/img/background/pen.png', '', ['class' => 'floating-item']); ?>
        </div>
        <div class="floating-wrapper layer-deep" style="top: 45%; left: 5%">
            <?php img_webp('/assets/img/background/eraser.png', '', ['class' => 'floating-item']); ?>
        </div>
        <div class="floating-wrapper layer-deep" style="top: 35%; right: 2%">
            <?php img_webp('/assets/img/background/pencil.png', '', ['class' => 'floating-item', 'style' => 'width: 20px; height: auto']); ?>
        </div>
        <div class="floating-wrapper layer-deep" style="top: 92%; left: 55%">
            <?php img_webp('/assets/img/background/notebook.png', '', ['class' => 'floating-item']); ?>
        </div>
    </div>

    <canvas id="particlesCanvas" class="particles-canvas"></canvas>

    <header class="main-header glass-effect">
        <div class="container header-wrapper">
            <div class="logo">
                <a href="<?= $baseUrl ?>"><img src="/assets/img/logo/faradj_logo.png" alt="Faradj MMC" /></a>
            </div>

            <nav class="nav-menu nav-menu-center" id="navMenu">
                <ul class="nav-list">
                    <li><a href="<?= $baseUrl ?>" class="nav-link<?= ($currentPath === '/' || $currentPath === '/index.php') ? ' active' : '' ?>"><?= t('nav.home') ?></a></li>
                    <li><a href="https://catalog.faradj.com" target="_blank" class="nav-link<?= $currentPage === 'catalog' ? ' active' : '' ?>"><?= t('nav.catalog') ?></a></li>
                    <li><a href="/events.php" class="nav-link<?= ($currentPath === '/events.php' || $currentPath === '/events') ? ' active' : '' ?>"><?= t('nav.events') ?></a></li>
                    <li><a href="/partners.php" class="nav-link<?= ($currentPath === '/partners.php' || $currentPath === '/partners') ? ' active' : '' ?>"><?= t('nav.partners') ?></a></li>
                    <li><a href="/contacts.php" class="nav-link<?= ($currentPath === '/contacts.php' || $currentPath === '/contacts') ? ' active' : '' ?>"><?= t('nav.contacts') ?></a></li>
                </ul>
                <a href="https://birmarket.az/ru/merchant/6882-qelem" target="_blank" class="btn-marketplace">BirMarket</a>
            </nav>

            <div class="nav-auth-group nav-auth-right">
                <div class="lang-switcher">
                    <button type="button" class="lang-btn <?= currentLang() === 'az' ? 'active' : '' ?>" data-lang="az">AZ</button>
                    <span class="lang-sep">|</span>
                    <button type="button" class="lang-btn <?= currentLang() === 'ru' ? 'active' : '' ?>" data-lang="ru">RU</button>
                    <span class="lang-sep">|</span>
                    <button type="button" class="lang-btn <?= currentLang() === 'en' ? 'active' : '' ?>" data-lang="en">EN</button>
                </div>
                <a href="/b2b.php" class="btn-b2b"><?= t('nav.request') ?></a>
            </div>

            <button class="burger-btn" id="burgerBtn" aria-label="Menyu" aria-expanded="false">
                <span class="burger-line"></span>
                <span class="burger-line"></span>
                <span class="burger-line"></span>
            </button>
        </div>
    </header>
    <div class="nav-overlay" id="navOverlay" aria-hidden="true"></div>

    <nav class="mobile-nav" id="mobileNav">
        <div class="mobile-nav-header">
            <img src="/assets/img/logo/faradj_logo.png" alt="Faradj" height="36">
            <button type="button" class="mobile-nav-close" id="mobileNavClose" aria-label="<?= t('common.close') ?>">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="mobile-nav-links">
            <a href="<?= $baseUrl ?>" class="mobile-nav-link<?= ($currentPath === '/' || $currentPath === '/index.php') ? ' active' : '' ?>">
                <i class="fas fa-home"></i>
                <?= t('nav.home') ?>
            </a>
            <a href="https://catalog.faradj.com" target="_blank" rel="noopener" class="mobile-nav-link<?= $currentPage === 'catalog' ? ' active' : '' ?>">
                <i class="fas fa-book-open"></i>
                <?= t('nav.catalog') ?>
            </a>
            <a href="/events.php" class="mobile-nav-link<?= ($currentPath === '/events.php' || $currentPath === '/events') ? ' active' : '' ?>">
                <i class="fas fa-newspaper"></i>
                <?= t('nav.events') ?>
            </a>
            <a href="/partners.php" class="mobile-nav-link<?= ($currentPath === '/partners.php' || $currentPath === '/partners') ? ' active' : '' ?>">
                <i class="fas fa-handshake"></i>
                <?= t('nav.partners') ?>
            </a>
            <a href="/contacts.php" class="mobile-nav-link<?= ($currentPath === '/contacts.php' || $currentPath === '/contacts') ? ' active' : '' ?>">
                <i class="fas fa-envelope"></i>
                <?= t('nav.contacts') ?>
            </a>
        </div>
        <div class="mobile-nav-divider"></div>
        <a href="https://birmarket.az/ru/merchant/6882-qelem" target="_blank" rel="noopener" class="mobile-nav-birmarket">BirMarket</a>
        <div class="mobile-nav-lang">
            <button type="button" class="lang-btn mobile-lang-btn <?= currentLang() === 'az' ? 'active' : '' ?>" data-lang="az">AZ</button>
            <button type="button" class="lang-btn mobile-lang-btn <?= currentLang() === 'ru' ? 'active' : '' ?>" data-lang="ru">RU</button>
            <button type="button" class="lang-btn mobile-lang-btn <?= currentLang() === 'en' ? 'active' : '' ?>" data-lang="en">EN</button>
        </div>
        <div class="mobile-nav-cta">
            <a href="/b2b.php" class="mobile-cta-btn">
                <i class="fas fa-paper-plane"></i>
                <?= t('nav.request') ?>
            </a>
        </div>
    </nav>
