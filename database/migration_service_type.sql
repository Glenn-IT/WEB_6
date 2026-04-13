-- ============================================================
-- MIGRATION: Service Type for Bookings (Walk-in / Home / Hotel)
-- Run this on your database (local XAMPP & InfinityFree)
-- ============================================================

ALTER TABLE `main_order`
  ADD COLUMN IF NOT EXISTS `service_type`  VARCHAR(50)  DEFAULT 'walk-in'  COMMENT 'walk-in | home | hotel'  AFTER `billing_address`,
  ADD COLUMN IF NOT EXISTS `hotel_name`    VARCHAR(255) DEFAULT NULL        COMMENT 'Hotel name (hotel service only)'  AFTER `service_type`,
  ADD COLUMN IF NOT EXISTS `hotel_room`    VARCHAR(100) DEFAULT NULL        COMMENT 'Room number (hotel service only)'  AFTER `hotel_name`;
