-- Guest favorites (login yoxdur — cookie token)
USE `vip_qr`;

CREATE TABLE IF NOT EXISTS `guest_favorites` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `guest_token` CHAR(64) NOT NULL,
  `product_id` INT UNSIGNED NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_guest_product` (`guest_token`, `product_id`),
  KEY `idx_guest_token` (`guest_token`),
  CONSTRAINT `fk_guest_favorites_product`
    FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
