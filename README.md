# VIP Karvan — QR Menyu

Şəki restoranı üçün rəqəmsal QR menyü sistemi (tək restoran, sifariş/ödəniş yoxdur).

## Tələblər

- PHP 8.2+
- MySQL
- Apache (`mod_rewrite`)
- Composer (QR kitabxanası üçün)

## Quraşdırma

1. Asılılıqlar:

```bash
composer install
```

2. Verilənlər bazası:

```bash
mysql -u root < database/schema.sql
mysql -u root < database/seed.sql
```

3. Konfiqurasiya: `config/database.php` və `config/app.php` (`url`).

4. Açın: `http://localhost/vipqr/` və ya `http://localhost/vipqr/public/`

## Admin

- URL: `/admin/login`
- İstifadəçi: `admin`
- Şifrə: `admin123`

## Struktur

```
public/     # Document root
app/        # Controllers, Models, Views, Core
config/
database/
```
