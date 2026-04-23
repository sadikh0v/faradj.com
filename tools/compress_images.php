<?php
set_time_limit(300);
ini_set('memory_limit', '256M');

$dirs = [
    __DIR__ . '/../public/assets/img/clients/',
    __DIR__ . '/../public/assets/img/brands/',
    __DIR__ . '/../public/assets/img/logo/',
];

$maxSize = 300;
$count = 0;

foreach ($dirs as $dir) {
    if (!is_dir($dir)) {
        continue;
    }

    foreach (glob($dir . '*.{png,jpg,jpeg}', GLOB_BRACE) as $path) {
        $info = @getimagesize($path);
        if (!$info) {
            continue;
        }

        [$w, $h, $type] = $info;
        if ($w <= $maxSize && $h <= $maxSize) {
            continue;
        }

        $ratio = min($maxSize / $w, $maxSize / $h);
        $newW = (int) ($w * $ratio);
        $newH = (int) ($h * $ratio);

        $src = match ($type) {
            IMAGETYPE_PNG => imagecreatefrompng($path),
            IMAGETYPE_JPEG => imagecreatefromjpeg($path),
            default => null,
        };
        if (!$src) {
            continue;
        }

        $dst = imagecreatetruecolor($newW, $newH);
        if ($type === IMAGETYPE_PNG) {
            imagealphablending($dst, false);
            imagesavealpha($dst, true);
            imagefilledrectangle(
                $dst,
                0,
                0,
                $newW,
                $newH,
                imagecolorallocatealpha($dst, 255, 255, 255, 127)
            );
        } else {
            $white = imagecolorallocate($dst, 255, 255, 255);
            imagefilledrectangle($dst, 0, 0, $newW, $newH, $white);
        }

        imagecopyresampled($dst, $src, 0, 0, 0, 0, $newW, $newH, $w, $h);

        match ($type) {
            IMAGETYPE_PNG => imagepng($dst, $path, 8),
            IMAGETYPE_JPEG => imagejpeg($dst, $path, 85),
            default => null,
        };

        imagedestroy($src);
        imagedestroy($dst);

        $newSize = round(filesize($path) / 1024);
        echo 'OK ' . basename($path) . " {$w}x{$h} -> {$newW}x{$newH} ({$newSize} KB)\n";
        $count++;
    }
}

echo "\nDone! Processed: {$count} files\n";
