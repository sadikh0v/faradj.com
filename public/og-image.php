<?php
$title    = substr(strip_tags($_GET['title'] ?? 'Faradj MMC'), 0, 60);
$subtitle = substr(strip_tags($_GET['sub'] ?? 'Dəftərxana ləvazimatları'), 0, 80);
$date     = $_GET['date'] ?? date('d.m.Y');

$cacheKey  = md5($title . $subtitle . $date);
$cachePath = dirname(__DIR__) . '/storage/og/' . $cacheKey . '.png';

if (!is_dir(dirname($cachePath))) {
    mkdir(dirname($cachePath), 0755, true);
}

if (file_exists($cachePath)) {
    header('Content-Type: image/png');
    header('Cache-Control: public, max-age=86400');
    readfile($cachePath);
    exit;
}

$w = 1200;
$h = 630;
$img = imagecreatetruecolor($w, $h);

// Фоновый градиент
for ($i = 0; $i < $h; $i++) {
    $ratio = $i / $h;
    $r = (int)(30 + (108 - 30) * $ratio);
    $g = (int)(30 + (99 - 30) * $ratio);
    $b = (int)(46 + (255 - 46) * $ratio);
    $c = imagecolorallocate($img, $r, $g, $b);
    imageline($img, 0, $i, $w, $i, $c);
}

// Overlay тёмный слой
imagealphablending($img, true);
$overlay = imagecolorallocatealpha($img, 0, 0, 0, 60);
imagefilledrectangle($img, 0, 0, $w, $h, $overlay);

// Логотип
$logoPath = __DIR__ . '/assets/img/logo/faradj_logo.png';
if (file_exists($logoPath)) {
    $logo = @imagecreatefrompng($logoPath);
    if ($logo) {
        $lw = imagesx($logo);
        $lh = imagesy($logo);
        $scale = 80 / max(1, $lh);
        $newW = (int)($lw * $scale);
        $resized = imagecreatetruecolor($newW, 80);
        if ($resized) {
            imagealphablending($resized, false);
            imagesavealpha($resized, true);
            imagecopyresampled($resized, $logo, 0, 0, 0, 0, $newW, 80, $lw, $lh);
            imagecopy($img, $resized, 60, 60, 0, 0, $newW, 80);
            imagedestroy($resized);
        }
        imagedestroy($logo);
    }
}

$white  = imagecolorallocate($img, 255, 255, 255);
$purple  = imagecolorallocate($img, 108, 99, 255);
$gray   = imagecolorallocate($img, 180, 180, 200);

// Категория-бейдж
$badgeBg = imagecolorallocatealpha($img, 108, 99, 255, 40);
imagefilledroundrect($img, 60, 170, 260, 210, 8, 8, $badgeBg);
$font = 5;
imagestring($img, $font, 80, 182, 'FARADJ MMC', $purple);

// Заголовок — разбить на строки по 35 символов
$words = explode(' ', $title);
$lines = [];
$line = '';
foreach ($words as $word) {
    if (strlen($line . ' ' . $word) > 32) {
        if ($line !== '') $lines[] = trim($line);
        $line = $word;
    } else {
        $line .= ($line === '' ? '' : ' ') . $word;
    }
}
if ($line !== '') $lines[] = trim($line);

$y = 230;
foreach (array_slice($lines, 0, 3) as $l) {
    imagestring($img, 5, 60, $y, $l, $white);
    $y += 32;
}

// Подзаголовок
$words2 = explode(' ', $subtitle);
$lines2 = [];
$line2 = '';
foreach ($words2 as $word) {
    if (strlen($line2 . ' ' . $word) > 50) {
        if ($line2 !== '') $lines2[] = trim($line2);
        $line2 = $word;
    } else {
        $line2 .= ($line2 === '' ? '' : ' ') . $word;
    }
}
if ($line2 !== '') $lines2[] = trim($line2);

$y2 = $y + 20;
foreach (array_slice($lines2, 0, 2) as $l) {
    imagestring($img, 3, 60, $y2, $l, $gray);
    $y2 += 24;
}

// Дата внизу
imagestring($img, 3, 60, $h - 60, $date, $gray);

// Линия снизу
$line_c = imagecolorallocate($img, 108, 99, 255);
imagesetthickness($img, 4);
imageline($img, 0, $h - 8, $w, $h - 8, $line_c);

imagepng($img, $cachePath);
imagedestroy($img);

header('Content-Type: image/png');
header('Cache-Control: public, max-age=86400');
readfile($cachePath);
exit;

function imagefilledroundrect($img, $x1, $y1, $x2, $y2, $rx, $ry, $color) {
    imagefilledrectangle($img, $x1 + $rx, $y1, $x2 - $rx, $y2, $color);
    imagefilledrectangle($img, $x1, $y1 + $ry, $x2, $y2 - $ry, $color);
    imagefilledellipse($img, $x1 + $rx, $y1 + $ry, $rx * 2, $ry * 2, $color);
    imagefilledellipse($img, $x2 - $rx, $y1 + $ry, $rx * 2, $ry * 2, $color);
    imagefilledellipse($img, $x1 + $rx, $y2 - $ry, $rx * 2, $ry * 2, $color);
    imagefilledellipse($img, $x2 - $rx, $y2 - $ry, $rx * 2, $ry * 2, $color);
}
