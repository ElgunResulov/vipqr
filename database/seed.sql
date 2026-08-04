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
('about', 'Şəkinin ən dadlı mətbəxi — VIP Karvan.'),
('about_ru', 'Самая вкусная кухня Шеки — VIP Karvan.'),
('about_en', 'The finest kitchen of Sheki — VIP Karvan.'),
('tagline', 'Şəkinin dadı · Karvan ənənəsi'),
('tagline_ru', 'Вкус Шеки · Традиция каравана'),
('tagline_en', 'Taste of Sheki · Caravan tradition'),
('heritage_story', 'Şəki — ipək yolu və karvansarayların şəhəri. VIP Karvan bu torpağın dadını, əsrlərin süfrə ənənəsini qonaqlarına təqdim edir.'),
('heritage_story_ru', 'Шеки — город Шёлкового пути и караван-сараев. VIP Karvan предлагает гостям вкус этой земли и традиции стола веков.'),
('heritage_story_en', 'Sheki — a city of the Silk Road and caravanserais. VIP Karvan brings you the taste of this land and centuries of table tradition.'),
('hero_video', ''),
('hero_poster', '');

-- Categories
INSERT INTO `categories` (`name`, `name_ru`, `name_en`, `slug`, `image`, `sort_order`, `is_active`) VALUES
('İsti yeməklər', 'Горячие блюда', 'Hot dishes', 'isti-yemekler', NULL, 1, 1),
('Salatlar', 'Салаты', 'Salads', 'salatlar', NULL, 2, 1),
('Şirniyyat', 'Сладости', 'Desserts', 'shirniyyat', NULL, 3, 1),
('İçkilər', 'Напитки', 'Drinks', 'ickiler', NULL, 4, 1);

-- Products (is_featured / is_popular / view_count)
INSERT INTO `products` (`category_id`, `name`, `name_ru`, `name_en`, `description`, `description_ru`, `description_en`, `price`, `image`, `sort_order`, `is_active`, `is_available`, `is_featured`, `is_popular`, `view_count`) VALUES
(1, 'Piti', 'Пити', 'Piti', 'Şəkinin əfsanəvi pitisı — quzu, noxud və göyərti ilə əsrlərin resepti.', 'Легендарный шекинский пити — баранина, нут и зелень.', 'Legendary Sheki piti — lamb, chickpeas and herbs.', 12.00, NULL, 1, 1, 1, 1, 0, 40),
(1, 'Dolma', 'Долма', 'Dolma', 'Üzüm yarpağında dolma — Qafqaz süfrəsinin klassikası, qatıq ilə.', 'Долма в виноградных листьях — классика кавказского стола.', 'Grape-leaf dolma — a Caucasian table classic.', 10.00, NULL, 2, 1, 1, 0, 1, 96),
(1, 'Kabab assoriti', 'Ассорти кебаб', 'Kebab assortment', 'Lülə, toyuq və quzu kabab — manqal ənənəsi.', 'Люля, курица и баранина — традиция мангала.', 'Lyulya, chicken and lamb — mangal tradition.', 18.00, NULL, 3, 1, 1, 0, 1, 128),
(1, 'Qutab', 'Кутаб', 'Gutab', 'Yaşıl qutab — göyərti və yağ ilə ev üsulu.', 'Зелёный кутаб с зеленью и маслом.', 'Green gutab with herbs and butter.', 4.50, NULL, 4, 1, 1, 0, 0, 74),
(2, 'Çoban salatı', 'Салат Чобан', 'Shepherd salad', 'Pomidor, xiyar, soğan və göyərti — bağdan süfrəyə.', 'Помидоры, огурцы, лук и зелень.', 'Tomato, cucumber, onion and herbs.', 5.00, NULL, 1, 1, 1, 0, 0, 22),
(2, 'Sezar salatı', 'Салат Цезарь', 'Caesar salad', 'Toyuq, aysberq, parmesan və sezar sousu.', NULL, NULL, 8.50, NULL, 2, 1, 1, 0, 0, 18),
(2, 'Manqal salatı', 'Мангал салат', 'Grill salad', 'İsti manqal tərəvəzləri ilə.', NULL, NULL, 7.00, NULL, 3, 1, 1, 0, 0, 15),
(3, 'Şəki halvasi', 'Шекинская халва', 'Sheki halva', 'Klassik Şəki halvasi — şəhərin şirin rəmzi.', 'Классическая шекинская халва — сладкий символ города.', 'Classic Sheki halva — the sweet symbol of the city.', 6.00, NULL, 1, 1, 1, 1, 0, 35),
(3, 'Pakhlava', 'Пахлава', 'Pakhlava', 'Badamlı pakhlava — 3 dilim, bayram dadı.', 'Пахлава с миндалём — 3 кусочка.', 'Almond pakhlava — 3 pieces.', 5.50, NULL, 2, 1, 1, 0, 0, 48),
(3, 'Ballı tort', 'Медовый торт', 'Honey cake', 'Ev üsulu ballı tort.', NULL, NULL, 4.00, NULL, 3, 1, 1, 0, 0, 12),
(4, 'Çay (dəstə)', 'Чай (набор)', 'Tea set', 'Ənənəvi Azərbaycan çayı — çayxana ruhu.', 'Традиционный азербайджанский чай.', 'Traditional Azerbaijani tea.', 3.00, NULL, 1, 1, 1, 0, 0, 61),
(4, 'Kompot', 'Компот', 'Kompot', 'Ev üsulu meyvə kompotu.', NULL, NULL, 2.50, NULL, 2, 1, 1, 0, 0, 9),
(4, 'Limonad', 'Лимонад', 'Lemonade', 'Təzə limonlu limonad.', NULL, NULL, 3.50, NULL, 3, 1, 1, 0, 0, 14),
(4, 'Mineral su', 'Минеральная вода', 'Mineral water', 'Soyuq mineral su 0.5L.', NULL, NULL, 1.50, NULL, 4, 1, 1, 0, 0, 8);
