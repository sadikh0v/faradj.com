<?php

class VisitorModel
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function track(): void
    {
        $lang = $_COOKIE['lang'] ?? 'az';
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $page = $_SERVER['REQUEST_URI'] ?? '/';
        $referrer = $_SERVER['HTTP_REFERER'] ?? '';
        $ua = $_SERVER['HTTP_USER_AGENT'] ?? '';

        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO visitors (ip_address, page, referrer, user_agent, lang)
                VALUES (?, ?, ?, ?, ?)
            ");
            $stmt->execute([$ip, $page, $referrer, $ua, $lang]);
        } catch (PDOException $e) {
            // Silently ignore if table doesn't exist
        }
    }

    public function getTotalCount(): int
    {
        try {
            return (int) $this->pdo->query("SELECT COUNT(*) FROM visitors")->fetchColumn();
        } catch (PDOException $e) {
            return 0;
        }
    }

    public function getTodayCount(): int
    {
        try {
            return (int) $this->pdo->query(
                "SELECT COUNT(*) FROM visitors WHERE DATE(visited_at) = CURDATE()"
            )->fetchColumn();
        } catch (PDOException $e) {
            return 0;
        }
    }

    public function getTopPages(int $limit = 5): array
    {
        try {
            $stmt = $this->pdo->prepare("
                SELECT page, COUNT(*) as cnt
                FROM visitors
                GROUP BY page
                ORDER BY cnt DESC
                LIMIT ?
            ");
            $stmt->execute([$limit]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function getVisitsByDay(int $days = 7): array
    {
        try {
            $stmt = $this->pdo->query("
                SELECT DATE(visited_at) as d, COUNT(*) as cnt
                FROM visitors
                WHERE visited_at >= DATE_SUB(CURDATE(), INTERVAL $days DAY)
                GROUP BY DATE(visited_at)
                ORDER BY d ASC
            ");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }
}
