<?php
if (!function_exists('setting')) {
    function setting(string $key, string $default = ''): string
    {
        static $cache = null;

        if ($cache === null) {
            $cache = [];
            try {
                if (!function_exists('db')) {
                    require_once base_path('db.php');
                }
                $rows = db()->query("SELECT key_name, value FROM settings")->fetchAll(PDO::FETCH_ASSOC);
                foreach ($rows as $row) {
                    $cache[$row['key_name']] = $row['value'];
                }
            } catch (Throwable $e) {}
        }

        return (string) ($cache[$key] ?? $default);
    }
}

if (!function_exists('setting_refresh')) {
    /** Сбросить кэш настроек. После redirect — новый запрос, кэш автоматически пуст. */
    function setting_refresh(): void
    {
        // Per-request cache: после header('Location:...') exit — новый запрос, кэш = null
    }
}
