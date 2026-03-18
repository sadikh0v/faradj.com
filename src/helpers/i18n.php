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

if (!function_exists('t_raw')) {
    /** Same as t() but returns raw string without htmlspecialchars (for HTML content). */
    function t_raw(string $key): string
    {
        static $translations = [];
        $lang = currentLang();
        if (!isset($translations[$lang])) {
            $file = base_path("src/lang/{$lang}.php");
            $translations[$lang] = file_exists($file)
                ? require $file
                : require base_path('src/lang/az.php');
        }
        return (string) ($translations[$lang][$key] ?? $key);
    }
}

if (!function_exists('faq_items')) {
    /** Returns FAQ items array for current language. DB first, then lang fallback. */
    function faq_items(): array
    {
        try {
            if (function_exists('db')) {
                $rows = db()->query("SELECT * FROM faqs WHERE is_active=1 ORDER BY sort_order ASC")->fetchAll(PDO::FETCH_ASSOC);
                if (!empty($rows)) {
                    $lang = currentLang();
                    $qKey = 'question_' . $lang;
                    $aKey = 'answer_' . $lang;
                    return array_map(function ($r) use ($qKey, $aKey) {
                        $q = $r[$qKey] ?? $r['question_az'] ?? '';
                        $a = $r[$aKey] ?? $r['answer_az'] ?? '';
                        return ['q' => $q, 'a' => $a];
                    }, array_filter($rows, function ($r) use ($qKey) {
                        return !empty(trim($r[$qKey] ?? $r['question_az'] ?? ''));
                    }));
                }
            }
        } catch (Throwable $e) {}
        $translations = [];
        $lang = currentLang();
        $file = base_path("src/lang/{$lang}.php");
        $translations[$lang] = file_exists($file) ? require $file : require base_path('src/lang/az.php');
        return (array) ($translations[$lang]['faq'] ?? []);
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
