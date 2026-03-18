<?php
/**
 * Deploy webhook — GitHub webhook və ya manual deploy
 * .env: DEPLOY_SECRET=your_secret, DEPLOY_GIT_PATH, DEPLOY_WEB_PATH (optional)
 */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit;
}

require_once __DIR__ . '/../src/load_env.php';

$secret = env('DEPLOY_SECRET', 'faradj_deploy_2024');

// GitHub webhook signature
$payload = file_get_contents('php://input');
$sig = 'sha256=' . hash_hmac('sha256', $payload, $secret);
$githubSig = $_SERVER['HTTP_X_HUB_SIGNATURE_256'] ?? '';

// Manual deploy with key in GET
$manualKey = $_GET['key'] ?? '';
$isGithub = $githubSig && hash_equals($sig, $githubSig);
$isManual = $manualKey === $secret;

if (!$isGithub && !$isManual) {
    http_response_code(403);
    exit('Forbidden');
}

$gitPath = env('DEPLOY_GIT_PATH', dirname(__DIR__));
$webPath = env('DEPLOY_WEB_PATH', dirname(__DIR__));

$out1 = $out2 = [];
exec('cd ' . escapeshellarg($gitPath) . ' && git pull origin main 2>&1', $out1);
if ($webPath !== $gitPath) {
    exec('cp -rf ' . escapeshellarg($gitPath . '/.') . ' ' . escapeshellarg($webPath) . ' 2>&1', $out2);
}

http_response_code(200);
header('Content-Type: text/plain');
echo "OK";
