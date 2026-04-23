<?php
/**
 * Output <picture> with WebP source and fallback for JPG/PNG
 * SVG and WebP pass through as plain <img>
 * Paths with spaces are URL-encoded to avoid srcset parsing errors.
 */
if (!function_exists('safe_img_path')) {
    function safe_img_path(string $path): string {
        $dir = dirname($path);
        $filename = basename($path);
        $encoded = rawurlencode($filename);
        return ($dir !== '.' ? $dir . '/' : '') . $encoded;
    }
}
if (!function_exists('img_webp')) {
function img_webp(string $src, string $alt = '', array $attrs = []): void
{
    $attrsStr = '';
    foreach ($attrs as $k => $v) {
        $attrsStr .= ' ' . htmlspecialchars($k) . '="' . htmlspecialchars($v) . '"';
    }
    $safeSrc = safe_img_path($src);
    if (preg_match('/\.(svg|webp)$/i', $src)) {
        echo '<img src="' . htmlspecialchars($safeSrc) . '" alt="' . htmlspecialchars($alt) . '"' . $attrsStr . '>';
        return;
    }
    if (preg_match('/\.(jpg|jpeg|png)$/i', $src)) {
        $webp = preg_replace('/\.(jpg|jpeg|png)$/i', '.webp', $src);
        $safeWebp = safe_img_path($webp);
        echo '<picture>';
        echo '<source srcset="' . htmlspecialchars($safeWebp) . '" type="image/webp">';
        echo '<img src="' . htmlspecialchars($safeSrc) . '" alt="' . htmlspecialchars($alt) . '"' . $attrsStr . '>';
        echo '</picture>';
        return;
    }
    echo '<img src="' . htmlspecialchars($safeSrc) . '" alt="' . htmlspecialchars($alt) . '"' . $attrsStr . '>';
}
}
