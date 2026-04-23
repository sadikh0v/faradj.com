<?php
// Запустить один раз для сжатия существующих изображений
set_time_limit(300);

$dirs = [
    __DIR__ . '/../public/assets/img/clients/',
    __DIR__ . '/../public/assets/img/brands/',
];

$maxW = 400;
$maxH = 400;
$count = 0;

foreach ($dirs as $dir) {
    foreach (glob($dir . '*.png') as $path) {
        $info = @getimagesize($path);
        if (!$info) {
            continue;
        }
        [$w, $h, $type] = $info;

        $ratio = min($maxW / $w, $maxH / $h);
        if ($ratio >= 1) {
            continue; // уже маленькое
        }

        $newW = (int) ($w * $ratio);
        $newH = (int) ($h * $ratio);

        $src = imagecreatefrompng($path);
        $dst = imagecreatetruecolor($newW, $newH);
        imagealphablending($dst, false);
        imagesavealpha($dst, true);
        $transparent = imagecolorallocatealpha($dst, 255, 255, 255, 127);
        imagefilledrectangle($dst, 0, 0, $newW, $newH, $transparent);
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $newW, $newH, $w, $h);
        imagepng($dst, $path, 8);
        imagedestroy($src);
        imagedestroy($dst);

        echo "✅ " . basename($path) . " ({$w}x{$h} → {$newW}x{$newH})\n";
        $count++;
    }
}
echo "\nDone! Обработано: $count файлов";
