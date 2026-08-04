-- Add multi-language columns (safe to re-run checks manually)
USE `vip_qr`;

ALTER TABLE `categories`
  ADD COLUMN `name_ru` VARCHAR(120) DEFAULT NULL AFTER `name`,
  ADD COLUMN `name_en` VARCHAR(120) DEFAULT NULL AFTER `name_ru`;

ALTER TABLE `products`
  ADD COLUMN `name_ru` VARCHAR(160) DEFAULT NULL AFTER `name`,
  ADD COLUMN `name_en` VARCHAR(160) DEFAULT NULL AFTER `name_ru`,
  ADD COLUMN `description_ru` TEXT DEFAULT NULL AFTER `description`,
  ADD COLUMN `description_en` TEXT DEFAULT NULL AFTER `description_ru`;
