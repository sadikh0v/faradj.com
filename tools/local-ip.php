<?php
/**
 * Show local IP for mobile testing
 * Run: php tools/local-ip.php
 */
$ip = 'tapılmadı';
if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
    $output = @shell_exec('ipconfig');
    if (preg_match('/IPv4[^:]*:\s*(\d+\.\d+\.\d+\.\d+)/', $output, $m)) {
        $ip = trim($m[1]);
    }
} else {
    $output = @shell_exec('ifconfig 2>/dev/null || ip addr 2>/dev/null');
    if (preg_match('/inet\s+(\d+\.\d+\.\d+\.\d+)/', $output, $m)) {
        $ip = $m[1];
    }
}
echo "Lokal IP: $ip\n";
echo "Telefonda aç: http://$ip:8000\n";
echo "\nServeri başlat: php -S 0.0.0.0:8000 -t public\n";
