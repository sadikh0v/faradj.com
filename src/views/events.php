<?php
$catLabels = [
    'xebərlər' => t('events.news'),
    'yeniləmə' => t('events.update'),
    'aksiyalar' => t('events.promo'),
    'şirkət' => t('events.company'),
    'tədbirlər' => t('events.events'),
    'sərgi' => t('events.sergi'),
    'festival' => t('events.festival'),
];
$events = $events ?? [];
$catKey = function($c) {
    $m = ['sərgi'=>'sergi','sergi'=>'sergi','festival'=>'festival','xebərlər'=>'xeberler','xəbərlər'=>'xeberler','xeberler'=>'xeberler','elanlar'=>'elanlar','yeniləmə'=>'yenileme','aksiyalar'=>'aksiyalar','şirkət'=>'sirket','tədbirlər'=>'tedbirler'];
    return $m[strtolower($c ?? '')] ?? strtolower($c ?? '');
};
?>
<main class="events-news-main">
    <section class="events-news-section">
        <div class="container">
            <h2 class="section-title text-center"><?= t('events.title') ?></h2>
            <div class="news-filters">
                <button class="filter-pill active" data-category="all"><?= t('events.all') ?></button>
                <?php foreach ($catLabels as $key => $label): ?>
                <button class="filter-pill" data-category="<?= htmlspecialchars($key) ?>"><?= htmlspecialchars($label) ?></button>
                <?php endforeach; ?>
            </div>
            <div class="news-grid" id="newsGrid">
                <?php foreach ($events as $e):
                    $date = $e['event_date'] ?? $e['created_at'] ?? '';
                    $cat = $e['category'] ?? 'xebərlər';
                    $img = $e['image_url'] ?? $e['image'] ?? null;
                    $eTitle = eventLang($e, 'title');
                    $eExcerpt = eventLang($e, 'excerpt');
                ?>
                <article class="news-card glass-card" data-category="<?= htmlspecialchars($cat) ?>">
                    <?php if ($img): ?>
                    <div class="news-card-img">
                        <img src="<?= htmlspecialchars($img) ?>" alt="<?= htmlspecialchars($eTitle) ?>" loading="lazy" />
                    </div>
                    <?php endif; ?>
                    <div class="news-card-body">
                        <?php $ck = $catKey($cat); $catLabel = t('category.' . $ck); $catLabel = ($catLabel !== 'category.' . $ck) ? $catLabel : ($catLabels[$cat] ?? htmlspecialchars($cat ?? '')); ?>
                        <span class="news-cat"><?= $catLabel ?></span>
                        <h3><?= htmlspecialchars($eTitle) ?></h3>
                        <?php if ($date): ?><span class="news-date"><?= htmlspecialchars($date) ?></span><?php endif; ?>
                        <p class="news-excerpt"><?= htmlspecialchars($eExcerpt) ?></p>
                        <button type="button" class="news-link news-open-modal" data-id="<?= (int)($e['id'] ?? 0) ?>"><?= t('events.read_more') ?> <i class="fas fa-arrow-right"></i></button>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>
            <?php if (empty($events)): ?>
            <p class="text-center" style="padding: 60px 20px; color: var(--text-light);"><?= t('events.no_news') ?></p>
            <?php endif; ?>
        </div>
    </section>
</main>

<div id="newsModal" class="modal-overlay hidden">
    <div class="modal-box glass-card">
        <button type="button" class="modal-close" aria-label="<?= t('events.close') ?>">&times;</button>
        <div class="modal-content" id="newsModalContent"></div>
        <div class="modal-footer">
            <span style="font-size:13px;color:#666;"><?= t('events.share') ?>:</span>
            <div class="share-btns">
                <a href="#" class="share-btn wa" target="_blank" rel="noopener" aria-label="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                <a href="#" class="share-btn tg" target="_blank" rel="noopener" aria-label="Telegram"><i class="fab fa-telegram-plane"></i></a>
            </div>
        </div>
        <button type="button" class="btn-close-modal"><?= t('events.modal_close') ?></button>
    </div>
</div>

<script>
window.i18nEvents = {
  close: <?= json_encode(t('events.modal_close')) ?>,
  share: <?= json_encode(t('events.modal_share')) ?>,
  date: <?= json_encode(t('events.modal_date')) ?>,
  author: <?= json_encode(t('events.modal_author')) ?>,
  cat: <?= json_encode(t('events.modal_cat')) ?>
};
window.__eventsData = <?= json_encode(array_map(function($e) use ($catLabels, $catKey) {
    $cat = $e['category'] ?? 'xebərlər';
    $ck = $catKey($cat);
    $lbl = t('category.' . $ck);
    $catLabel = ($lbl !== 'category.' . $ck) ? $lbl : ($catLabels[$cat] ?? $cat);
    return [
        'id' => (int)($e['id'] ?? 0),
        'title' => eventLang($e, 'title'),
        'excerpt' => eventLang($e, 'excerpt'),
        'full_text' => eventLang($e, 'full_text'),
        'category' => $cat,
        'category_label' => $catLabel,
        'author' => $e['author'] ?? '',
        'event_date' => $e['event_date'] ?? $e['created_at'] ?? '',
        'image_url' => $e['image_url'] ?? $e['image'] ?? null,
    ];
}, $events)) ?>;
</script>
