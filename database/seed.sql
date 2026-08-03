-- VIP Karvan QR Menyu — seed data
USE `vip_qr`;

SET NAMES utf8mb4;

-- Admin: username=admin  password=admin123
INSERT INTO `admins` (`username`, `email`, `password_hash`) VALUES
('admin', 'admin@vipkarvan.az', '$2y$10$B1K67TcQHVDOExr7Zf5.BOl1S8Lx5.zJxnWLYdkEKeFuJ0GfX0YY.');

-- Settings
INSERT INTO `settings` (`setting_key`, `setting_value`) VALUES
('restaurant_name', 'VIP Karvan'),
('phone', '050 607 55 54'),
('address', 'Mirzə Fətəli Axundov küçəsi, Şəki'),
('logo', ''),
('working_hours', '09:00 – 23:00'),
('about', 'Şəkinin ən dadlı mətbəxi — VIP Karvan.');

-- Categories
INSERT INTO `categories` (`name`, `slug`, `image`, `sort_order`, `is_active`) VALUES
('İsti yeməklər', 'isti-yemekler', NULL, 1, 1),
('Salatlar', 'salatlar', NULL, 2, 1),
('Şirniyyat', 'shirniyyat', NULL, 3, 1),
('İçkilər', 'ickiler', NULL, 4, 1);

-- Products
INSERT INTO `products` (`category_id`, `name`, `description`, `price`, `image`, `sort_order`, `is_active`, `is_available`) VALUES
(1, 'Piti', 'Ənənəvi Şəki pitisı — quzu əti, noxud və təzə göyərti ilə.', 12.00, NULL, 1, 1, 1),
(1, 'Dolma', 'Üzüm yarpağında dolma, qatıq ilə.', 10.00, NULL, 2, 1, 1),
(1, 'Kabab assoriti', 'Lülə, toyuq və quzu kabab qarışığı.', 18.00, NULL, 3, 1, 1),
(1, 'Qutab', 'Yaşıl qutab — göyərti və yağ ilə.', 4.50, NULL, 4, 1, 1),
(2, 'Çoban salatı', 'Pomidor, xiyar, soğan və göyərti.', 5.00, NULL, 1, 1, 1),
(2, 'Sezar salatı', 'Toyuq, aysberq, parmesan və sezar sousu.', 8.50, NULL, 2, 1, 1),
(2, 'Manqal salatı', 'İsti manqal tərəvəzləri ilə.', 7.00, NULL, 3, 1, 1),
(3, 'Şəki halvası', 'Klassik Şəki halvasi.', 6.00, NULL, 1, 1, 1),
(3, 'Pakhlava', 'Badamlı pakhlava — 3 dilim.', 5.50, NULL, 2, 1, 1),
(3, 'Ballı tort', 'Ev üsulu ballı tort.', 4.00, NULL, 3, 1, 1),
(4, 'Çay (dəstə)', 'Ənənəvi Azərbaycan çayı.', 3.00, NULL, 1, 1, 1),
(4, 'Kompot', 'Ev üsulu meyvə kompotu.', 2.50, NULL, 2, 1, 1),
(4, 'Limonad', 'Təzə limonlu limonad.', 3.50, NULL, 3, 1, 1),
(4, 'Mineral su', 'Soyuq mineral su 0.5L.', 1.50, NULL, 4, 1, 1);
