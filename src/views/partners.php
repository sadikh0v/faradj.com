<?php
// load_env, i18n и db уже подключены в index.php
// Дополнительные данные для страницы партнёров

$brands = [];
$clients = [];
$suppliers = [];

try {
$brands = db()->query(
    "SELECT * FROM brands WHERE is_active=1 ORDER BY sort_order, id"
)->fetchAll(PDO::FETCH_ASSOC);

$clients = db()->query(
    "SELECT * FROM clients WHERE is_active=1 ORDER BY sort_order, id"
)->fetchAll(PDO::FETCH_ASSOC);

$suppliers = db()->query(
    "SELECT * FROM suppliers WHERE is_active=1 ORDER BY sort_order ASC"
)->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {}

$lang = currentLang();
?>
<main class="partners-main">
    <section class="suppliers-map-section">
        <div class="container">
            <div class="section-header">
                <h2><?= t('partners.suppliers_title') ?></h2>
                <div class="section-divider"></div>
            </div>
            <div class="suppliers-map-wrap glass-card">
                <div id="suppliersMap" style="height:420px;border-radius:16px;"></div>
            </div>
        </div>
    </section>

    <section class="partners-section brands-section">
        <div class="container">
            <div class="partners-divider section-header">
                <div class="divider-line"></div>
                <div class="divider-content glass-card">
                    <i class="fas fa-boxes"></i>
                    <span><?= t('partners.brands_section') ?></span>
                </div>
                <div class="divider-line"></div>
            </div>

            <div id="skeletonBrands" class="brands-grid">
                <?php for ($i = 0; $i < 6; $i++): ?>
                <div class="skeleton-brand-card">
                    <div class="skeleton skeleton-brand-logo"></div>
                    <div class="skeleton skeleton-brand-name"></div>
                    <div class="skeleton skeleton-brand-badge"></div>
                </div>
                <?php endfor; ?>
            </div>
            <div class="brands-grid" id="brandsGrid" style="display:none;">
                <?php foreach ($brands as $brand): ?>
                <div class="brand-card glass-card">
                    <?php if (!empty($brand['logo'])): ?>
                    <div class="brand-card-logo"><?php img_webp($brand['logo'], $brand['name'], ['loading' => 'lazy', 'width' => '105', 'height' => '105']); ?></div>
                    <?php endif; ?>
                    <h4><?= htmlspecialchars($brand['name']) ?></h4>
                    <span class="card-badge badge-<?= ($brand['badge'] ?? 'partner') === 'distributor' ? 'distributor' : 'terefdas' ?>"><?= t('partners.' . ($brand['badge'] ?? 'partner')) ?></span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <div class="partners-divider">
        <div class="divider-line"></div>
        <div class="divider-content glass-card">
            <i class="fas fa-handshake"></i>
            <span><?= t('partners.clients_section') ?></span>
        </div>
        <div class="divider-line"></div>
    </div>

    <section class="partners-section clients-section">
        <div class="container">
            <div id="skeletonClients" class="clients-grid">
                <?php for ($i = 0; $i < 4; $i++): ?>
                <div class="skeleton-brand-card">
                    <div class="skeleton skeleton-brand-logo"></div>
                    <div class="skeleton skeleton-brand-name"></div>
                    <div class="skeleton skeleton-brand-badge"></div>
                </div>
                <?php endfor; ?>
            </div>
            <div class="clients-grid" id="clientsGrid" style="display:none;">
                <?php foreach ($clients as $client): ?>
                <div class="client-card glass-card" data-client="<?= htmlspecialchars($client['slug'] ?? $client['id']) ?>">
                    <?php if (!empty($client['logo'])): ?>
                    <div class="client-card-logo"><?php img_webp($client['logo'], $client['name'], ['loading' => 'lazy', 'width' => '105', 'height' => '105']); ?></div>
                    <?php endif; ?>
                    <h4><?= htmlspecialchars($client['name']) ?></h4>
                    <?php if (!empty($client['badge'])): ?>
                    <span class="card-badge client-badge badge-<?= htmlspecialchars($client['badge']) ?>"><?= t('partners.badge.' . $client['badge']) ?></span>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
</main>

<script>
window.suppliersFromDB = <?= json_encode(array_map(function($s) use ($lang) {
    $nameKey = 'country_' . $lang;
    $name = !empty($s[$nameKey]) ? $s[$nameKey] : ($s['country_az'] ?? '');
    return [
        'coords'   => [(float)$s['latitude'], (float)$s['longitude']],
        'name'     => $name,
        'brands'   => $s['brands'] ?? '',
        'type'     => $s['type'] ?? 'partner',
        'flag'     => $s['flag'] ?? '',
        'iso_code' => $s['iso_code'] ?? '',
    ];
}, $suppliers), JSON_UNESCAPED_UNICODE) ?>;
</script>
