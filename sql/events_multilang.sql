-- Add multilingual columns to events table
ALTER TABLE `events`
  ADD COLUMN `title_ru` varchar(255) DEFAULT NULL AFTER `title`,
  ADD COLUMN `title_en` varchar(255) DEFAULT NULL AFTER `title_ru`,
  ADD COLUMN `excerpt_ru` varchar(500) DEFAULT NULL AFTER `excerpt`,
  ADD COLUMN `excerpt_en` varchar(500) DEFAULT NULL AFTER `excerpt_ru`,
  ADD COLUMN `full_text_ru` text DEFAULT NULL AFTER `full_text`,
  ADD COLUMN `full_text_en` text DEFAULT NULL AFTER `full_text_ru`;
