<?php
/**
 * Convert JPG/JPEG/PNG images to WebP format
 * Run: php tools/convert-webp.php
 */
$dirs = [
    __DIR__ . '/../public/assets/img',
];

$converted = 0;
$errors = 0;

function convertToWebp(string $source): bool
{
    $info = @getimagesize($source);
    if (!$info) return false;

    $webpPath = preg_replace('/\.(jpg|jpeg|png)$/i', '.webp', $source);

    switch ($info['mime']) {
        case 'image/jpeg':
            $img = @imagecreatefromjpeg($source);
            break;
        case 'image/png':
            $img = @imagecreatefrompng($source);
            if (!$img) return false;
            imagepalettetotruecolor($img);
            imagealphablending($img, true);
            imagesavealpha($img, true);
            break;
        default:
            return false;
    }

    if (!$img) return false;
    $result = imagewebp($img, $webpPath, 85);
    imagedestroy($img);
    return $result;
}

function scanDirRecursive(string $dir): void
{
    global $converted, $errors;
    if (!is_dir($dir)) return;
    $items = @scandir($dir);
    if (!$items) return;
    foreach ($items as $item) {
        if ($item === '.' || $item === '..') continue;
        $path = $dir . DIRECTORY_SEPARATOR . $item;
        if (is_dir($path)) {
            scanDirRecursive($path);
        } elseif (preg_match('/\.(jpg|jpeg|png)$/i', $item)) {
            $webp = preg_replace('/\.(jpg|jpeg|png)$/i', '.webp', $path);
            if (!file_exists($webp)) {
                if (convertToWebp($path)) {
                    echo "✅ $item\n";
                    $converted++;
                } else {
                    echo "❌ $item\n";
                    $errors++;
                }
            } else {
                echo "⏭ $item (уже есть)\n";
            }
        }
    }
}

foreach ($dirs as $dir) {
    if (is_dir($dir)) {
        echo "Scanning: $dir\n";
        scanDirRecursive($dir);
    }
}

echo "\n✅ Конвертировано: $converted\n";
echo "❌ Ошибок: $errors\n";
