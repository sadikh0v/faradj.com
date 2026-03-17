<?php
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');
require_once __DIR__ . '/../src/load_env.php';
require_once __DIR__ . '/../src/helpers/i18n.php';
$currentPage = 'thank-you';
$metaTitle = t('request.success') . ' — Faradj MMC';
$metaDescription = t('request.success');
require __DIR__ . '/../src/views/header.php';
?>
<section class="b2b-section" style="text-align:center;padding:80px 0;">
    <div class="container">
        <div class="b2b-success glass-card" style="max-width:500px;margin:0 auto;">
            <i class="fas fa-check-circle" style="font-size:4rem;color:#25d366;margin-bottom:20px;"></i>
            <h2><?= t('request.success') ?></h2>
            <p style="color:var(--text-light);margin-bottom:24px;"><?= t('thank_you.subtitle') ?></p>
            <a href="/" class="btn-submit" style="display:inline-flex;text-decoration:none;"><?= t('nav.home') ?></a>
        </div>
    </div>
</section>
<?php require __DIR__ . '/../src/views/footer.php'; ?>
