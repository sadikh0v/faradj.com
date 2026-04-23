<?php
set_time_limit(300);
ini_set('memory_limit', '512M');

$files = [
    __DIR__ . '/../public/assets/img/clients/client_69ccfd0162508.png',
    __DIR__ . '/../public/assets/img/clients/client_69c78ca4418e8.png',
    __DIR__ . '/../public/assets/img/clients/client_69ccefc82724f.png',
    __DIR__ . '/../public/assets/img/clients/client_69ccfbc9140fc.png',
    __DIR__ . '/../public/assets/img/clients/client_69c66164a74fe.png',
    __DIR__ . '/../public/assets/img/logo/faradj_logo.png',
];

$maxSize = 200;

foreach ($files as $path) {
    if (!file_exists($path)) {
        echo "Not found: $path\n";
        continue;
    }

    $info = @getimagesize($path);
    if (!$info) {
        continue;
    }
    [$w, $h, $type] = $info;

    $before = round(filesize($path) / 1024);
    $ratio = min($maxSize / $w, $maxSize / $h);
    if ($ratio >= 1) {
        echo "Skip: " . basename($path) . " already small\n";
        continue;
    }

    $newW = (int)($w * $ratio);
    $newH = (int)($h * $ratio);

    $src = match ($type) {
        IMAGETYPE_PNG => imagecreatefrompng($path),
        IMAGETYPE_JPEG => imagecreatefromjpeg($path),
        default => null
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
    }
    imagecopyresampled($dst, $src, 0, 0, 0, 0, $newW, $newH, $w, $h);

    match ($type) {
        IMAGETYPE_PNG => imagepng($dst, $path, 9),
        IMAGETYPE_JPEG => imagejpeg($dst, $path, 80),
        default => null
    };

    imagedestroy($src);
    imagedestroy($dst);

    $after = round(filesize($path) / 1024);
    echo basename($path) . ": {$before}KB -> {$after}KB\n";
}

echo "\nDone!\n";
