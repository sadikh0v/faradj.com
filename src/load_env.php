<?php
if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(__DIR__));
}

if (!function_exists('env')) {
    function env(string $key, $default = null) {
        static $env = null;
        if ($env === null) {
            $env = [];
            $path = BASE_PATH . '/.env';
            if (is_file($path)) {
                $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                foreach ($lines as $line) {
                    if (strpos(trim($line), '#') === 0) continue;
                    if (strpos($line, '=') !== false) {
                        [$k, $v] = explode('=', $line, 2);
                        $env[trim($k)] = trim($v, " \t\n\r\0\x0B\"'");
                    }
                }
            }
        }
        return $env[$key] ?? $default;
    }
}

if (!function_exists('base_path')) {
    function base_path(string $path = ''): string {
        $base = rtrim(BASE_PATH, '/\\');
        $path = ltrim(str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $path), '/\\');
        return $path ? $base . DIRECTORY_SEPARATOR . $path : $base;
    }
}

require_once __DIR__ . '/helpers/settings.php';
require_once __DIR__ . '/helpers/csrf.php';
