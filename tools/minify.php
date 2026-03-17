<?php
/**
 * Minify CSS and JS for production
 * Run: php tools/minify.php
 */
function minifyCSS(string $css): string
{
    $css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css);
    $css = preg_replace('/\s*([{}|:;,>~+])\s*/', '$1', $css);
    $css = preg_replace('/\s+/', ' ', $css);
    $css = str_replace(';}', '}', $css);
    return trim($css);
}

function minifyJS(string $js): string
{
    $js = preg_replace('/\/\/[^\n]*\n/', "\n", $js);
    $js = preg_replace('/\/\*[\s\S]*?\*\//', '', $js);
    $js = preg_replace('/[ \t]+/', ' ', $js);
    $js = preg_replace('/\n\s*\n/', "\n", $js);
    return trim($js);
}

$base = __DIR__ . '/../';
$files = [
    'css' => [
        'public/assets/css/style.css',
        'public/assets/css/tablet.css',
        'public/assets/css/mobile.css',
        'public/assets/css/events.css',
        'public/assets/css/partners.css',
        'public/assets/css/contacts.css',
        'public/assets/css/b2b.css',
    ],
    'js' => [
        'public/assets/js/script.js',
        'public/assets/js/app.js',
        'public/assets/js/events.js',
        'public/assets/js/partners.js',
        'public/assets/js/contacts.js',
        'public/assets/js/forms.js',
    ],
];

foreach ($files['css'] as $file) {
    $path = $base . $file;
    if (!file_exists($path)) {
        echo "⏭ CSS skip (not found): $file\n";
        continue;
    }
    $original = file_get_contents($path);
    $minified = minifyCSS($original);
    $minPath = str_replace('.css', '.min.css', $path);
    file_put_contents($minPath, $minified);
    $saving = round((1 - strlen($minified) / strlen($original)) * 100);
    echo "✅ CSS: $file → {$saving}% azaldıldı\n";
}

foreach ($files['js'] as $file) {
    $path = $base . $file;
    if (!file_exists($path)) {
        echo "⏭ JS skip (not found): $file\n";
        continue;
    }
    $original = file_get_contents($path);
    $minified = minifyJS($original);
    $minPath = str_replace('.js', '.min.js', $path);
    file_put_contents($minPath, $minified);
    $saving = round((1 - strlen($minified) / strlen($original)) * 100);
    echo "✅ JS: $file → {$saving}% azaldıldı\n";
}

echo "\nBitdi! .min faylları yaradıldı.\n";
