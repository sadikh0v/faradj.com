<?php
require_once __DIR__ . '/../load_env.php';
require_once base_path('src/helpers/i18n.php');
require_once base_path('db.php');

$brands = [];
$clients = [];
$suppliers = [];

try {
    $brands = $pdo->query(
        "SELECT * FROM brands WHERE is_active=1 ORDER BY sort_order, id"
    )->fetchAll(PDO::FETCH_ASSOC);

    $clients = $pdo->query(
        "SELECT * FROM clients WHERE is_active=1 ORDER BY sort_order, id"
    )->fetchAll(PDO::FETCH_ASSOC);

    $suppliers = $pdo->query(
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
                    <div class="brand-card-logo"><?php img_webp($brand['logo'], $brand['name']); ?></div>
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
                    <div class="client-card-logo"><?php img_webp($client['logo'], $client['name']); ?></div>
                    <?php endif; ?>
                    <h4><?= htmlspecialchars($client['name']) ?></h4>
                    <?php if (!empty($client['badge'])): ?>
                    <span class="card-badge client-badge badge-<?= htmlspecialchars($client['badge']) ?>"><?= t('partners.badge.' . $client['badge']) ?></span>
                    <?php endif; ?>
                    <button type="button" class="client-read-more"><?= t('partners.read_more') ?> <i class="fas fa-arrow-right"></i></button>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
</main>

<div id="clientModal" class="modal-overlay hidden" aria-hidden="true">
    <div class="modal-box glass-card modal-client" role="dialog">
        <button type="button" class="modal-close" aria-label="<?= t('partners.close') ?>">&times;</button>
        <div class="modal-client-logo"></div>
        <h2 class="modal-client-name"></h2>
        <div class="modal-client-sections"></div>
        <p class="modal-client-date"></p>
        <button type="button" class="btn-close-modal modal-close-btn">
            <i class="fas fa-times"></i> <?= t('partners.close') ?>
        </button>
    </div>
</div>
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
window.i18nPartners = {
  products: <?= json_encode(t('partners.products_label')) ?>,
  problems: <?= json_encode(t('partners.problems_label')) ?>,
  requests: <?= json_encode(t('partners.requests_label')) ?>,
  rating: <?= json_encode(t('partners.rating_label')) ?>,
  since: <?= json_encode(t('partners.since_label')) ?>
};
window.clientData = <?= json_encode([
  'xxi' => ['name' => t('partners.client_xxi_name'), 'logo' => '/assets/img/clients/21 esr.png', 'alir' => t('partners.client_xxi_alir'), 'hell' => t('partners.client_xxi_hell'), 'sorğu' => t('partners.client_xxi_req'), 'rating' => 5, 'date' => '2015'],
  'agri' => ['name' => 'AGRI', 'logo' => '/assets/img/clients/AGRI.png', 'alir' => t('partners.client_agri_alir'), 'hell' => t('partners.client_agri_hell'), 'sorğu' => t('partners.client_agri_req'), 'rating' => 5, 'date' => '2018'],
  'ktv' => ['name' => 'KTV', 'logo' => '/assets/img/clients/KTV.jpg', 'alir' => t('partners.client_ktv_alir'), 'hell' => t('partners.client_ktv_hell'), 'sorğu' => t('partners.client_ktv_req'), 'rating' => 5, 'date' => '2016'],
  'cotton' => ['name' => 'Cotton Club', 'logo' => '/assets/img/clients/Cotton.png', 'alir' => t('partners.client_cotton_alir'), 'hell' => t('partners.client_cotton_hell'), 'sorğu' => t('partners.client_cotton_req'), 'rating' => 4, 'date' => '2019'],
  'shams' => ['name' => 'Shams', 'logo' => '/assets/img/clients/shams.png', 'alir' => t('partners.client_shams_alir'), 'hell' => t('partners.client_shams_hell'), 'sorğu' => t('partners.client_shams_req'), 'rating' => 5, 'date' => '2017'],
  'ze' => ['name' => t('partners.client_ze_name'), 'logo' => '/assets/img/clients/ZE.png', 'alir' => t('partners.client_ze_alir'), 'hell' => t('partners.client_ze_hell'), 'sorğu' => t('partners.client_ze_req'), 'rating' => 5, 'date' => '2020'],
  'agrs' => ['name' => 'AGRS', 'logo' => '/assets/img/clients/AGRS.png', 'alir' => t('partners.client_agrs_alir'), 'hell' => t('partners.client_agrs_hell'), 'sorğu' => t('partners.client_agrs_req'), 'rating' => 4, 'date' => '2018'],
  'bsm' => ['name' => t('partners.client_bsm_name'), 'logo' => '/assets/img/clients/BSM.png', 'alir' => t('partners.client_bsm_alir'), 'hell' => t('partners.client_bsm_hell'), 'sorğu' => t('partners.client_bsm_req'), 'rating' => 5, 'date' => '2014'],
  'edg' => ['name' => 'EuroDesign', 'logo' => '/assets/img/clients/EDG.jpg', 'alir' => t('partners.client_edg_alir'), 'hell' => t('partners.client_edg_hell'), 'sorğu' => t('partners.client_edg_req'), 'rating' => 5, 'date' => '2019'],
  'fze' => ['name' => 'FZE', 'logo' => '/assets/img/clients/fze.png', 'alir' => t('partners.client_fze_alir'), 'hell' => t('partners.client_fze_hell'), 'sorğu' => t('partners.client_fze_req'), 'rating' => 4, 'date' => '2017'],
  'azf' => ['name' => 'AzerFloat', 'logo' => '/assets/img/clients/azf.jpg', 'alir' => t('partners.client_azf_alir'), 'hell' => t('partners.client_azf_hell'), 'sorğu' => t('partners.client_azf_req'), 'rating' => 4, 'date' => '2018'],
  'ovcular' => ['name' => t('partners.client_ovcular_name'), 'logo' => '/assets/img/clients/Ovcular.png', 'alir' => t('partners.client_ovcular_alir'), 'hell' => t('partners.client_ovcular_hell'), 'sorğu' => t('partners.client_ovcular_req'), 'rating' => 4, 'date' => '2016'],
]) ?>;
</script>
