<?php
header('Content-Type: application/xml; charset=utf-8');
require_once __DIR__ . '/../src/load_env.php';
require_once __DIR__ . '/../db.php';

$base = 'https://faradj.com';
$langs = ['az', 'ru', 'en'];
$pages = ['', '/events', '/partners', '/contacts', '/b2b', '/privacy'];

echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:xhtml="http://www.w3.org/1999/xhtml">

<?php foreach ($pages as $page): ?>
  <url>
    <loc><?= $base . $page ?></loc>
    <lastmod><?= date('Y-m-d') ?></lastmod>
    <changefreq><?= $page === '' ? 'daily' : 'weekly' ?></changefreq>
    <priority><?= $page === '' ? '1.0' : '0.8' ?></priority>
    <?php foreach ($langs as $lang): ?>
    <xhtml:link rel="alternate" hreflang="<?= $lang ?>"
                href="<?= $base . $page ?>"/>
    <?php endforeach; ?>
  </url>
<?php endforeach; ?>

<?php
// Добавить страницы новостей
try {
    $events = db()->query("SELECT id, slug, created_at FROM events WHERE is_published=1 ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
    foreach ($events as $e):
        $slug = trim($e['slug'] ?? '');
        $loc = $slug ? $base . '/events/' . htmlspecialchars($slug) : $base . '/events?id=' . (int)$e['id'];
?>
  <url>
    <loc><?= $loc ?></loc>
    <lastmod><?= date('Y-m-d', strtotime($e['created_at'] ?? 'now')) ?></lastmod>
    <changefreq>monthly</changefreq>
    <priority>0.6</priority>
  </url>
<?php endforeach;
} catch (PDOException $e) {}
?>

</urlset>
