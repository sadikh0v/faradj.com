<?php
if (!function_exists('setting')) {
    function setting(string $key, string $default = ''): string
    {
        static $cache = null;
        if ($cache === null) {
            try {
                global $pdo;
                if (!isset($pdo)) {
                    require_once base_path('db.php');
                }
                $rows = $pdo->query("SELECT key_name, value FROM settings")->fetchAll(PDO::FETCH_ASSOC);
                $cache = array_column($rows, 'value', 'key_name');
            } catch (Throwable $e) {
                $cache = [];
            }
        }
        return (string) ($cache[$key] ?? $default);
    }
}
