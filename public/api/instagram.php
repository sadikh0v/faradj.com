<?php
require_once __DIR__ . '/../../src/load_env.php';

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

$token = env('INSTAGRAM_TOKEN');
$cacheFile = dirname(__DIR__, 2) . '/storage/instagram_cache.json';

if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < 3600) {
    echo file_get_contents($cacheFile);
    exit;
}

if (empty($token)) {
    echo json_encode(['posts' => [], 'error' => 'no_token']);
    exit;
}

$url = 'https://graph.instagram.com/me/media' .
    '?fields=id,caption,media_type,media_url,thumbnail_url,permalink,timestamp' .
    '&limit=6&access_token=' . urlencode($token);

$ch = curl_init($url);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 10,
    CURLOPT_SSL_VERIFYPEER => true,
]);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode !== 200) {
    echo json_encode(['posts' => [], 'error' => 'api_error']);
    exit;
}

$data = json_decode($response, true);
$posts = $data['data'] ?? [];
$result = json_encode(['posts' => $posts]);

if (!empty($posts)) {
    if (!is_dir(dirname($cacheFile))) {
        mkdir(dirname($cacheFile), 0755, true);
    }
    file_put_contents($cacheFile, $result);
}

echo $result;
