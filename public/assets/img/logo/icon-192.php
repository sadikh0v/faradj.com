<?php
header('Content-Type: image/png');
header('Cache-Control: public, max-age=86400');
$s = 192;
$logoPath = __DIR__ . '/faradj_logo_favicon.png';
if (file_exists($logoPath) && ($src = @imagecreatefrompng($logoPath))) {
    $w = imagesx($src);
    $h = imagesy($src);
    $dst = imagecreatetruecolor($s, $s);
    if ($dst) {
        imagealphablending($dst, false);
        imagesavealpha($dst, true);
        $trans = imagecolorallocatealpha($dst, 0, 0, 0, 127);
        imagefill($dst, 0, 0, $trans);
        imagealphablending($dst, true);
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $s, $s, $w, $h);
        imagepng($dst);
        imagedestroy($dst);
        imagedestroy($src);
        exit;
    }
    imagedestroy($src);
}
$img = imagecreatetruecolor($s, $s);
$bg = imagecolorallocate($img, 108, 99, 255);
imagefill($img, 0, 0, $bg);
$white = imagecolorallocate($img, 255, 255, 255);
imagestring($img, 5, (int)(($s - 60) / 2), (int)(($s - 20) / 2), 'Faradj', $white);
imagepng($img);
imagedestroy($img);
