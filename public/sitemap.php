<?php
require_once __DIR__ . '/../src/load_env.php';
require_once __DIR__ . '/../db.php';

header('Content-Type: application/xml; charset=utf-8');

$routes = ['/', '/events.php', '/partners.php', '/contacts.php', '/b2b.php', '/privacy.php'];
$langs = ['az', 'ru', 'en'];
$base = 'https://faradj.com';

echo '<?xml version="1.0" encoding="UTF-8"?>';
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">';

foreach ($routes as $route) {
    $path = $route === '/' ? '/' : $route;
    echo '<url>';
    echo '<loc>' . htmlspecialchars($base . $path) . '</loc>';
    echo '<changefreq>weekly</changefreq>';
    echo '<priority>' . ($route === '/' ? '1.0' : '0.8') . '</priority>';
    foreach ($langs as $lang) {
        echo '<xhtml:link rel="alternate" hreflang="' . $lang . '" href="' . htmlspecialchars($base . $path) . '"/>';
    }
    echo '</url>';
}

try {
    $events = $pdo->query("SELECT id, created_at FROM events WHERE is_published=1")->fetchAll(PDO::FETCH_ASSOC);
    foreach ($events as $e) {
        echo '<url>';
        echo '<loc>' . htmlspecialchars($base . '/events.php?id=' . (int)$e['id']) . '</loc>';
        echo '<changefreq>monthly</changefreq>';
        echo '<priority>0.6</priority>';
        echo '<lastmod>' . date('Y-m-d', strtotime($e['created_at'] ?? 'now')) . '</lastmod>';
        echo '</url>';
    }
} catch (Throwable $e) {
    // ignore
}

echo '</urlset>';
