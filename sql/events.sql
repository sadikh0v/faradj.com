-- Events table for mini-blog (Tədbirlər)
CREATE TABLE IF NOT EXISTS `events` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `title_ru` varchar(255) DEFAULT NULL,
  `title_en` varchar(255) DEFAULT NULL,
  `excerpt` varchar(500) DEFAULT NULL,
  `excerpt_ru` varchar(500) DEFAULT NULL,
  `excerpt_en` varchar(500) DEFAULT NULL,
  `full_text` text DEFAULT NULL,
  `full_text_ru` text DEFAULT NULL,
  `full_text_en` text DEFAULT NULL,
  `category` varchar(50) NOT NULL DEFAULT 'xebərlər',
  `image_url` varchar(500) DEFAULT NULL,
  `author` varchar(100) DEFAULT NULL,
  `event_date` date DEFAULT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_published` (`is_published`),
  KEY `idx_category` (`category`),
  KEY `idx_event_date` (`event_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Seed default events (optional)
INSERT INTO `events` (`title`, `excerpt`, `full_text`, `category`, `author`, `event_date`, `is_published`) VALUES
('Məktəb Mövsümü Açılışı', 'Yeni tədris ilinə özəl kampaniya.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Yeni tədris ilinə özəl kampaniya çərçivəsində müştərilərimizə xüsusi təkliflər təqdim edirik.', 'şirkət', 'Faradj MMC', '2026-08-25', 1),
('Baku Expo Sərgisi', 'Sərgidə yeni məhsullar təqdim edildi.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sərgidə yeni məhsullar təqdim edildi. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.', 'tədbirlər', 'Faradj MMC', '2026-04-10', 1),
('DOMS Yeni Kolleksiya', 'DOMS brendinin yaz kolleksiyası gəldi.', 'Lorem ipsum dolor sit amet. DOMS brendinin yaz kolleksiyası gəldi. Consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'yeniləmə', 'Faradj MMC', '2026-03-01', 1),
('Yazlıq Endirim', '20% endirim bütün məktəb ləvazimatlarına.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. 20% endirim bütün məktəb ləvazimatlarına. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'aksiyalar', 'Faradj MMC', '2026-02-14', 1),
('Ofis Ləvazimatları Xəbərləri', 'Yeni ofis məhsulları kataloqda.', 'Lorem ipsum dolor sit amet. Yeni ofis məhsulları kataloqda. Consectetur adipiscing elit, sed do eiusmod tempor incididunt.', 'xebərlər', 'Faradj MMC', '2026-01-20', 1),
('Dubai Sərgisi 2026', 'Beynəlxalq sərgi - yeni tərəfdaşlar.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Beynəlxalq sərgi - yeni tərəfdaşlar. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris.', 'tədbirlər', 'Faradj MMC', '2026-01-05', 1);
