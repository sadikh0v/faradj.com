<?php
$_settings_cache = null;

if (!function_exists('setting')) {
    function setting(string $key, string $default = ''): string
    {
        global $_settings_cache;

        if ($_settings_cache === null) {
            $_settings_cache = [];
            try {
                if (!function_exists('db')) {
                    require_once base_path('db.php');
                }
                $rows = db()->query("SELECT key_name, value FROM settings")->fetchAll(PDO::FETCH_ASSOC);
                foreach ($rows as $row) {
                    $_settings_cache[$row['key_name']] = $row['value'];
                }
            } catch (Throwable $e) {}
        }

        return (string) ($_settings_cache[$key] ?? $default);
    }
}

if (!function_exists('setting_clear_cache')) {
    function setting_clear_cache(): void
    {
        global $_settings_cache;
        $_settings_cache = null;
    }
}

if (!function_exists('setting_refresh')) {
    function setting_refresh(): void
    {
        setting_clear_cache();
    }
}
