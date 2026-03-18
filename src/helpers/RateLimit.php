<?php
class RateLimit
{
    private static function key(string $action): string
    {
        $ip = $_SERVER['HTTP_CF_CONNECTING_IP']
            ?? $_SERVER['HTTP_X_FORWARDED_FOR']
            ?? $_SERVER['REMOTE_ADDR']
            ?? 'unknown';
        if (strpos($ip, ',') !== false) {
            $ip = trim(explode(',', $ip)[0]);
        }
        return 'rate_' . $action . '_' . md5($ip);
    }

    public static function check(string $action, int $max = 5, int $window = 300): bool
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $key = self::key($action);
        $now = time();

        $data = $_SESSION[$key] ?? ['count' => 0, 'reset' => $now + $window];

        if ($now > $data['reset']) {
            $data = ['count' => 0, 'reset' => $now + $window];
        }

        if ($data['count'] >= $max) {
            return false;
        }

        $data['count']++;
        $_SESSION[$key] = $data;
        return true;
    }

    public static function reset(string $action): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        unset($_SESSION[self::key($action)]);
    }
}
