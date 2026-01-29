-- Run these SQL commands to add remaining premium feature fields

-- Add featured fields to re_properties (is_featured already exists)
ALTER TABLE `re_properties`
ADD COLUMN `featured_until` TIMESTAMP NULL AFTER `is_featured`,
ADD COLUMN `priority_score` INT DEFAULT 0 AFTER `featured_until`;

-- Create saved searches table
CREATE TABLE IF NOT EXISTS `re_saved_searches` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `account_id` BIGINT UNSIGNED NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `search_criteria` JSON NOT NULL,
  `alert_frequency` ENUM('instant', 'daily', 'weekly') DEFAULT 'daily',
  `is_active` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`account_id`) REFERENCES `re_accounts`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create property analytics table
CREATE TABLE IF NOT EXISTS `re_property_analytics` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `property_id` BIGINT UNSIGNED NOT NULL,
  `event_type` ENUM('view', 'contact', 'phone_click', 'email_click', 'whatsapp_click') NOT NULL,
  `user_ip` VARCHAR(255) NULL,
  `user_agent` TEXT NULL,
  `referrer` VARCHAR(255) NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`property_id`) REFERENCES `re_properties`(`id`) ON DELETE CASCADE,
  INDEX `idx_property_event` (`property_id`, `event_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
