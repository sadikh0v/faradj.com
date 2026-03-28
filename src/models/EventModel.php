<?php

require_once __DIR__ . '/../helpers/events_schema.php';

class EventModel
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll(): array
    {
        $pub = events_published_column_name();
        $stmt = $this->pdo->prepare("
            SELECT * FROM events 
            WHERE `$pub` = 1 
            ORDER BY event_date DESC, created_at DESC
        ");
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map('normalize_event_row', $rows);
    }

    public function getByCategory(string $category): array
    {
        $pub = events_published_column_name();
        $stmt = $this->pdo->prepare("
            SELECT * FROM events 
            WHERE `$pub` = 1 AND category = ?
            ORDER BY event_date DESC, created_at DESC
        ");
        $stmt->execute([$category]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map('normalize_event_row', $rows);
    }

    public function getById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM events WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? normalize_event_row($row) : null;
    }

    public function create(array $data): int
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO events (title, excerpt, full_text, category, image_url, author, event_date, is_published)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $data['title'] ?? '',
            $data['excerpt'] ?? '',
            $data['full_text'] ?? '',
            $data['category'] ?? 'xebərlər',
            $data['image_url'] ?? null,
            $data['author'] ?? null,
            $data['event_date'] ?? null,
            isset($data['is_published']) ? (int)$data['is_published'] : 1,
        ]);
        return (int)$this->pdo->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->pdo->prepare("
            UPDATE events SET
                title = ?, excerpt = ?, full_text = ?, category = ?,
                image_url = ?, author = ?, event_date = ?, is_published = ?
            WHERE id = ?
        ");
        return $stmt->execute([
            $data['title'] ?? '',
            $data['excerpt'] ?? '',
            $data['full_text'] ?? '',
            $data['category'] ?? 'xebərlər',
            $data['image_url'] ?? null,
            $data['author'] ?? null,
            $data['event_date'] ?? null,
            isset($data['is_published']) ? (int)$data['is_published'] : 1,
            $id,
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM events WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function getAllForAdmin(): array
    {
        $stmt = $this->pdo->query("
            SELECT * FROM events 
            ORDER BY event_date DESC, created_at DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
