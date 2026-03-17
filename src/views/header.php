<?php
require_once __DIR__ . '/../load_env.php';
require_once __DIR__ . '/../helpers/i18n.php';
require_once __DIR__ . '/../helpers/img_webp.php';
$currentPage = $currentPage ?? 'index';
$isProduction = (function_exists('env') && env('APP_ENV') === 'production');
$assetSuffix = $isProduction ? '.min' : '';
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
$baseHost = (!empty($_SERVER['HTTP_HOST']) && strpos($_SERVER['HTTP_HOST'], 'localhost') !== false)
    ? 'http://' . $_SERVER['HTTP_HOST']
    : 'https://faradj.com';
$metaTitle = $metaTitle ?? 'Faradj MMC — Biznes və Yaradıcılıq üçün İlham';
$metaDesc = $metaDescription ?? 'Azərbaycanın aparıcı dəftərxana və ofis ləvazimatları təchizatçısı. DOMS rəsmi distribyutoru.';
$metaUrl = $metaUrl ?? $baseHost . ($_SERVER['REQUEST_URI'] ?? '/');

// OG Image — динамическое превью
$ogTitle = $metaTitle;
$ogSub   = $metaDesc;
$ogDate  = date('d.m.Y');
if (isset($event) && is_array($event)) {
    $ogTitle = $event['title'] ?? $metaTitle;
    $ogSub   = $event['excerpt'] ?? $metaDesc;
    $ogDate  = $event['event_date'] ?? date('d.m.Y');
}
$ogTitleEnc = urlencode($ogTitle);
$ogSubEnc   = urlencode($ogSub);
$ogDateEnc  = urlencode($ogDate);
$metaImg = $metaImage ?? "{$baseHost}/og-image.php?title={$ogTitleEnc}&sub={$ogSubEnc}&date={$ogDateEnc}";
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
    <link rel="canonical" href="<?= htmlspecialchars($metaUrl) ?>" />

    <!-- Open Graph -->
    <meta property="og:type" content="website" />
    <meta property="og:url" content="<?= htmlspecialchars($metaUrl) ?>" />
    <meta property="og:title" content="<?= htmlspecialchars($metaTitle) ?>" />
    <meta property="og:description" content="<?= htmlspecialchars($metaDesc) ?>" />
    <meta property="og:image" content="<?= htmlspecialchars($metaImg) ?>" />
    <meta property="og:image:width" content="1200" />
    <meta property="og:image:height" content="630" />
    <meta property="og:locale" content="az_AZ" />
    <meta property="og:site_name" content="Faradj MMC" />

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="<?= htmlspecialchars($metaTitle) ?>" />
    <meta name="twitter:description" content="<?= htmlspecialchars($metaDesc) ?>" />
    <meta name="twitter:image" content="<?= htmlspecialchars($metaImg) ?>" />

    <title><?= htmlspecialchars($metaTitle) ?></title>

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
    <?php $gaId = env('GA_MEASUREMENT_ID'); if ($gaId): ?>
    <!-- Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=<?= htmlspecialchars($gaId) ?>"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', '<?= htmlspecialchars($gaId) ?>', { anonymize_ip: true, cookie_flags: 'SameSite=None;Secure' });
      document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('b2bForm')?.addEventListener('submit', function() {
          gtag('event', 'form_submit', { event_category: 'Müraciət', event_label: 'Müraciət' });
        });
        document.getElementById('contactForm')?.addEventListener('submit', function() {
          gtag('event', 'form_submit', { event_category: 'Contact', event_label: 'Əlaqə formu' });
        });
        document.querySelector('.whatsapp-float')?.addEventListener('click', function() {
          gtag('event', 'click', { event_category: 'WhatsApp', event_label: 'WhatsApp button' });
        });
      });
    </script>
    <?php endif; ?>
    <?php $ymId = env('YANDEX_COUNTER_ID'); if ($ymId): ?>
    <!-- Yandex Metrica -->
    <script type="text/javascript">
      (function(m,e,t,r,i,k,a){ m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)}; m[i].l=1*new Date();
      k=e.createElement(t); a=e.getElementsByTagName(t)[0]; k.async=1; k.src=r; a.parentNode.insertBefore(k,a)
      })(window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");
      ym(<?= (int)$ymId ?>, "init", { clickmap: true, trackLinks: true, accurateTrackBounce: true, webvisor: true });
    </script>
    <noscript><div><img src="https://mc.yandex.ru/watch/<?= (int)$ymId ?>" style="position:absolute;left:-9999px;" alt=""></div></noscript>
    <?php endif; ?>
    <?php $hjId = env('HOTJAR_ID'); if ($hjId): ?>
    <!-- Hotjar / Contentsquare -->
    <script src="https://t.contentsquare.net/uxa/<?= htmlspecialchars($hjId) ?>.js" async></script>
    <?php endif; ?>
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
