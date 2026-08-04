-- Append to seed: after main inserts, run translation updates
-- (kept here for documentation; main seed.sql has AZ content)

USE `vip_qr`;

UPDATE categories SET name_ru='Горячие блюда', name_en='Hot dishes' WHERE slug='isti-yemekler';
UPDATE categories SET name_ru='Салаты', name_en='Salads' WHERE slug='salatlar';
UPDATE categories SET name_ru='Сладости', name_en='Desserts' WHERE slug='shirniyyat';
UPDATE categories SET name_ru='Напитки', name_en='Drinks' WHERE slug='ickiler';
