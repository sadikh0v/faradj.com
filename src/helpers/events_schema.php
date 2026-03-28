<?php
/**
 * Поддержка двух схем таблицы events:
 * - Новая (sql/events.sql): excerpt, image_url, is_published, author
 * - Старая (tools/migrate.php): short_text, image, is_active, без author
 */
if (!function_exists('events_table_columns')) {
    function events_table_columns(): array
    {
        static $cols = null;
        if ($cols !== null) {
            return $cols;
        }
        $cols = [];
        try {
            if (!function_exists('db')) {
                require_once base_path('db.php');
            }
            $r = db()->query('SHOW COLUMNS FROM events');
            if ($r) {
                while ($row = $r->fetch(PDO::FETCH_ASSOC)) {
                    $cols[$row['Field']] = true;
                }
            }
        } catch (Throwable $e) {
        }
        return $cols;
    }
}

if (!function_exists('events_has_column')) {
    function events_has_column(string $name): bool
    {
        return isset(events_table_columns()[$name]);
    }
}

if (!function_exists('events_excerpt_column_names')) {
    function events_excerpt_column_names(): array
    {
        if (events_has_column('excerpt')) {
            return [
                'excerpt' => 'excerpt',
                'excerpt_ru' => 'excerpt_ru',
                'excerpt_en' => 'excerpt_en',
            ];
        }
        return [
            'excerpt' => 'short_text',
            'excerpt_ru' => 'short_text_ru',
            'excerpt_en' => 'short_text_en',
        ];
    }
}

if (!function_exists('events_image_column_name')) {
    function events_image_column_name(): string
    {
        if (events_has_column('image_url')) {
            return 'image_url';
        }
        if (events_has_column('image')) {
            return 'image';
        }
        return 'image_url';
    }
}

if (!function_exists('events_published_column_name')) {
    function events_published_column_name(): string
    {
        if (events_has_column('is_published')) {
            return 'is_published';
        }
        if (events_has_column('is_active')) {
            return 'is_active';
        }
        return 'is_published';
    }
}

if (!function_exists('normalize_event_row')) {
    function normalize_event_row(array $row): array
    {
        if (!isset($row['excerpt']) && isset($row['short_text'])) {
            $row['excerpt'] = $row['short_text'];
        }
        if (!isset($row['excerpt_ru']) && isset($row['short_text_ru'])) {
            $row['excerpt_ru'] = $row['short_text_ru'];
        }
        if (!isset($row['excerpt_en']) && isset($row['short_text_en'])) {
            $row['excerpt_en'] = $row['short_text_en'];
        }
        if (!isset($row['image_url']) && isset($row['image'])) {
            $row['image_url'] = $row['image'];
        }
        if (!isset($row['is_published']) && isset($row['is_active'])) {
            $row['is_published'] = (int) $row['is_active'];
        }
        return $row;
    }
}
