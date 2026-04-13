-- ============================================================
-- MIGRATION: Banner, Promo, and About Management Tables
-- Run this on your database (local XAMPP & InfinityFree)
-- ============================================================

-- --------------------------------------------------------
-- Table: site_banners  (sliding banner images on homepage)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `site_banners` (
  `banner_id`   INT(11)       NOT NULL AUTO_INCREMENT,
  `title`       VARCHAR(255)  DEFAULT NULL COMMENT 'Optional caption/title',
  `image_path`  VARCHAR(500)  NOT NULL    COMMENT 'Relative path to uploaded image',
  `sort_order`  INT(11)       DEFAULT 0   COMMENT 'Display order (ASC)',
  `is_active`   TINYINT(1)    DEFAULT 1   COMMENT '1=visible, 0=hidden',
  `deleted`     TINYINT(1)    DEFAULT 0,
  `created_at`  DATETIME      DEFAULT CURRENT_TIMESTAMP,
  `updated_at`  DATETIME      DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`banner_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Seed with the 5 existing hero images
INSERT INTO `site_banners` (`title`, `image_path`, `sort_order`, `is_active`) VALUES
('Banner 1', 'src/images/banner/Hero 1.jpg', 1, 1),
('Banner 2', 'src/images/banner/Hero 2.jpg', 2, 1),
('Banner 3', 'src/images/banner/Hero 3.jpg', 3, 1),
('Banner 4', 'src/images/banner/Hero 4.jpg', 4, 1),
('Banner 5', 'src/images/banner/Hero 5.jpg', 5, 1);


-- --------------------------------------------------------
-- Table: site_promos  (promotional cards shown below banner)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `site_promos` (
  `promo_id`      INT(11)       NOT NULL AUTO_INCREMENT,
  `title`         VARCHAR(255)  NOT NULL,
  `description`   TEXT          DEFAULT NULL,
  `image_path`    VARCHAR(500)  DEFAULT NULL,
  `discount_text` VARCHAR(100)  DEFAULT NULL COMMENT 'e.g. "20% OFF"',
  `link_service_id` INT(11)     DEFAULT NULL COMMENT 'Optional FK to items.item_id',
  `is_active`     TINYINT(1)    DEFAULT 1    COMMENT '1=visible, 0=hidden',
  `deleted`       TINYINT(1)    DEFAULT 0,
  `created_at`    DATETIME      DEFAULT CURRENT_TIMESTAMP,
  `updated_at`    DATETIME      DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`promo_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- --------------------------------------------------------
-- Table: site_about  (editable About Us content)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `site_about` (
  `about_id`     INT(11)      NOT NULL AUTO_INCREMENT,
  `section_key`  VARCHAR(100) NOT NULL UNIQUE COMMENT 'e.g. "main", "contact_address"',
  `label`        VARCHAR(255) DEFAULT NULL,
  `content`      LONGTEXT     DEFAULT NULL,
  `updated_at`   DATETIME     DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`about_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Seed default about data
INSERT INTO `site_about` (`section_key`, `label`, `content`) VALUES
('main_title',        'Page Title',          'Touch and Care Massage and Spa'),
('paragraph_1',       'Paragraph 1',         'At Touch and Care Massage and Spa, we believe that healing starts with the power of touch and the warmth of genuine care. Our spa is a peaceful sanctuary dedicated to helping you relax, recharge, and restore your mind and body.'),
('paragraph_2',       'Paragraph 2',         'Founded with a passion for wellness and holistic beauty, we offer a wide range of services—from Swedish, Shiatsu, hot stone, ventosa, and therapeutic massages to nail care, skin care, and hair treatment. Each session is delivered by skilled and certified professionals who prioritize your comfort, privacy, and overall well-being.'),
('paragraph_3',       'Paragraph 3',         'We don\'t just offer massages—we create a full self-care experience. Whether you\'re relieving stress, treating your skin, pampering your nails, or nourishing your hair, we\'re here to make every visit rejuvenating and memorable.'),
('paragraph_4',       'Paragraph 4',         'Come and experience the perfect harmony of touch and care—where your beauty and relaxation are always our priority.'),
('contact_address',   'Address',             'JPF7+M72, Diversion Road, Tuguegarao City, Cagayan'),
('contact_phone',     'Phone',               '09356724821'),
('contact_email',     'Email',               'touchandcaremassageandspa@gmail.com'),
('map_embed',         'Google Maps Embed URL', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3833.4203437870614!2d121.73087!3d17.6130967!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMTfCsDM2JzQ3LjEiTiAxMjHCsDQzJzUxLjEiRQ!5e0!3m2!1sen!2sph!4v1234567890');
