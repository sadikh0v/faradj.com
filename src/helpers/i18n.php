<?php
if (!function_exists('setLang')) {
    function setLang(string $lang): void
    {
        $_COOKIE['lang'] = $lang;
    }
}

if (!function_exists('currentLang')) {
    function currentLang(): string
    {
        $allowed = ['az', 'ru', 'en'];
        $lang = $_COOKIE['lang'] ?? 'az';
        return in_array($lang, $allowed, true) ? $lang : 'az';
    }
}

if (!function_exists('t')) {
    function t(string $key, array $replace = []): string
    {
        static $translations = [];

        $lang = currentLang();

        if (!isset($translations[$lang])) {
            $file = base_path("src/lang/{$lang}.php");
            $translations[$lang] = file_exists($file)
                ? require $file
                : require base_path('src/lang/az.php');
        }

        $text = $translations[$lang][$key] ?? $key;

        foreach ($replace as $k => $v) {
            $text = str_replace('{' . $k . '}', (string) $v, $text);
        }

        return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('eventLang')) {
    /** Get localized event field (title, excerpt, full_text). AZ uses base field, RU/EN use _ru/_en suffix. */
    function eventLang(array $event, string $field): string
    {
        $lang = currentLang();
        if ($lang === 'az') {
            return (string) ($event[$field] ?? '');
        }
        $suffix = $lang === 'ru' ? '_ru' : '_en';
        $localized = $event[$field . $suffix] ?? null;
        return trim((string) ($localized !== null && $localized !== '' ? $localized : ($event[$field] ?? '')));
    }
}
