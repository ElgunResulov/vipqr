-- Popular ranking columns (existing DBs only — skip if columns already exist)
USE `vip_qr`;

-- Run once on live DB that was created before is_popular/view_count.
-- Fresh installs: use schema.sql + seed.sql instead.

ALTER TABLE `products`
  ADD COLUMN `is_popular` TINYINT(1) NOT NULL DEFAULT 0 AFTER `is_featured`,
  ADD COLUMN `view_count` INT UNSIGNED NOT NULL DEFAULT 0 AFTER `is_popular`,
  ADD KEY `idx_products_popular` (`is_popular`, `view_count`, `is_active`);

UPDATE `products` SET `is_popular` = 1, `view_count` = 128 WHERE `id` = 3;
UPDATE `products` SET `is_popular` = 1, `view_count` = 96 WHERE `id` = 2;
UPDATE `products` SET `view_count` = 74 WHERE `id` = 4;
UPDATE `products` SET `view_count` = 61 WHERE `id` = 11;
UPDATE `products` SET `view_count` = 48 WHERE `id` = 9;
