<?php
/**
 * Resize cursor PNGs to smaller size
 * Run: php tools/resize-cursors.php
 */
$dir = __DIR__ . '/../public/assets/img/cursors/';
$maxSize = 28; // max 28px for cursor

foreach (['cursor.png', 'pointer.png'] as $file) {
    $path = $dir . $file;
    if (!file_exists($path)) continue;
    $img = @imagecreatefrompng($path);
    if (!$img) {
        echo "❌ Cannot read: $file\n";
        continue;
    }
    $w = imagesx($img);
    $h = imagesy($img);
    $scale = min($maxSize / $w, $maxSize / $h, 1);
    $nw = max(16, (int)($w * $scale));
    $nh = max(16, (int)($h * $scale));
    $dst = imagecreatetruecolor($nw, $nh);
    if (!$dst) {
        imagedestroy($img);
        continue;
    }
    imagealphablending($dst, false);
    imagesavealpha($dst, true);
    $trans = imagecolorallocatealpha($dst, 0, 0, 0, 127);
    imagefill($dst, 0, 0, $trans);
    imagealphablending($dst, true);
    imagecopyresampled($dst, $img, 0, 0, 0, 0, $nw, $nh, $w, $h);
    imagepng($dst, $path);
    imagedestroy($dst);
    imagedestroy($img);
    echo "✅ $file → {$nw}x{$nh}\n";
}
echo "Bitdi!\n";
