-- ============================================================
-- MIGRATION: Therapist Photo & Team Group Photo
-- Run this on your database (local XAMPP & InfinityFree)
-- ============================================================

-- Add photo, bio, and position columns to existing therapist table
ALTER TABLE `therapist`
  ADD COLUMN IF NOT EXISTS `photo`    VARCHAR(500) DEFAULT NULL  COMMENT 'Profile photo path',
  ADD COLUMN IF NOT EXISTS `bio`      TEXT         DEFAULT NULL  COMMENT 'Short bio/description',
  ADD COLUMN IF NOT EXISTS `position` VARCHAR(255) DEFAULT NULL  COMMENT 'e.g. Senior Therapist';

-- --------------------------------------------------------
-- Table: site_team_photo  (group / team photo on About page)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `site_team_photo` (
  `id`          INT(11)      NOT NULL AUTO_INCREMENT,
  `image_path`  VARCHAR(500) NOT NULL,
  `caption`     VARCHAR(255) DEFAULT NULL,
  `is_active`   TINYINT(1)   DEFAULT 1,
  `deleted`     TINYINT(1)   DEFAULT 0,
  `created_at`  DATETIME     DEFAULT CURRENT_TIMESTAMP,
  `updated_at`  DATETIME     DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
