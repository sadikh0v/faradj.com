<?php
header('Content-Type: image/jpeg');
header('Cache-Control: public, max-age=86400');

$w = 1200;
$h = 630;
$img = imagecreatetruecolor($w, $h);

// Gradient: #e0c3fc (224,195,252) -> #8ec5fc (142,197,252)
for ($y = 0; $y < $h; $y++) {
    $ratio = $y / $h;
    $r = (int)(224 + $ratio * (142 - 224));
    $g = (int)(195 + $ratio * (197 - 195));
    $b = 252;
    $c = imagecolorallocate($img, $r, $g, $b);
    imageline($img, 0, $y, $w, $y, $c);
}

$dark = imagecolorallocate($img, 43, 88, 118);
$accent = imagecolorallocate($img, 255, 101, 132);

$title = 'Faradj MMC';
$tagline = 'Qelem Stationery | DOMS Resmi Distribyutor';
$font = 5;
$tw = imagefontwidth($font) * strlen($title);
imagestring($img, $font, (int)(($w - $tw) / 2), (int)($h / 2 - 30), $title, $dark);
$tw2 = imagefontwidth($font) * strlen($tagline);
imagestring($img, $font, (int)(($w - $tw2) / 2), (int)($h / 2 + 5), $tagline, $accent);

imagejpeg($img, null, 90);
imagedestroy($img);
