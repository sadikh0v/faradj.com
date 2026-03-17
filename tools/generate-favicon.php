<?php
/**
 * Generate favicon sizes from qelem_logo.png
 * Run: php tools/generate-favicon.php
 */
$source = __DIR__ . '/../public/assets/img/logo/faradj_logo_favicon.png';
$outputDir = __DIR__ . '/../public/';

if (!file_exists($source)) {
    echo "❌ Source not found: $source\n";
    exit(1);
}

$sourceImg = @imagecreatefrompng($source);
if (!$sourceImg) {
    echo "❌ Cannot read PNG: $source\n";
    exit(1);
}
// Preserve transparency
imagealphablending($sourceImg, false);
imagesavealpha($sourceImg, true);

$srcW = imagesx($sourceImg);
$srcH = imagesy($sourceImg);

// Zoom in: use 44% of source (center crop) — larger logo
$crop = 0.44;
$cropW = (int)($srcW * $crop);
$cropH = (int)($srcH * $crop);
$srcX = (int)(($srcW - $cropW) / 2);
$srcY = (int)(($srcH - $cropH) / 2);

$sizes = [16, 32, 48, 64, 96, 128, 180, 192, 512];

foreach ($sizes as $size) {
    $dst = imagecreatetruecolor($size, $size);
    if (!$dst) continue;

    imagealphablending($dst, false);
    imagesavealpha($dst, true);
    $transparent = imagecolorallocatealpha($dst, 0, 0, 0, 127);
    imagefill($dst, 0, 0, $transparent);
    imagealphablending($dst, true);

    imagecopyresampled($dst, $sourceImg, 0, 0, $srcX, $srcY, $size, $size, $cropW, $cropH);

    $filename = "favicon-{$size}x{$size}.png";
    if ($size === 180) $filename = 'apple-touch-icon.png';

    imagepng($dst, $outputDir . $filename);
    imagedestroy($dst);
    echo "✅ $filename yaradıldı\n";
}

imagedestroy($sourceImg);

// favicon.ico — copy 32x32 as fallback (browsers may accept PNG content)
if (file_exists($outputDir . 'favicon-32x32.png')) {
    copy($outputDir . 'favicon-32x32.png', $outputDir . 'favicon.ico');
    echo "✅ favicon.ico (32x32 PNG) yaradıldı\n";
}

echo "\nBitdi!\n";
