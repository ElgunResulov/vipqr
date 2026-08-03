<?php
$settings = $settings ?? [];
?>
<!DOCTYPE html>
<html lang="az">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?= e(($settings['restaurant_name'] ?? 'VIP Karvan') . ' rəqəmsal menyü — Şəki') ?>">
    <title><?= e($title ?? 'VIP Karvan') ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&family=Figtree:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= e(asset('css/menu.css')) ?>?v=2" rel="stylesheet">
</head>
<body class="menu-body">
    <header class="menu-topbar" id="menuTopbar">
        <div class="container topbar-inner">
            <a class="brand-link" href="<?= e(url()) ?>">
                <?php if (!empty($settings['logo'])): ?>
                    <img src="<?= e(upload_url($settings['logo'])) ?>" alt="<?= e($settings['restaurant_name'] ?? 'VIP Karvan') ?>" class="brand-logo">
                <?php else: ?>
                    <span class="brand-name"><?= e($settings['restaurant_name'] ?? 'VIP Karvan') ?></span>
                <?php endif; ?>
            </a>
            <?php if (!empty($settings['phone'])): ?>
                <a class="topbar-phone" href="tel:<?= e(preg_replace('/\s+/', '', $settings['phone'])) ?>">
                    <?= e($settings['phone']) ?>
                </a>
            <?php endif; ?>
        </div>
    </header>

    <main>
        <?= $content ?>
    </main>

    <footer class="menu-footer">
        <div class="container footer-inner">
            <p class="footer-brand"><?= e($settings['restaurant_name'] ?? 'VIP Karvan') ?></p>
            <?php if (!empty($settings['address'])): ?>
                <p class="footer-meta"><?= e($settings['address']) ?></p>
            <?php endif; ?>
            <p class="footer-meta">
                <?php if (!empty($settings['working_hours'])): ?>
                    <?= e($settings['working_hours']) ?>
                <?php endif; ?>
                <?php if (!empty($settings['phone'])): ?>
                    <?php if (!empty($settings['working_hours'])): ?> · <?php endif; ?>
                    <a href="tel:<?= e(preg_replace('/\s+/', '', $settings['phone'])) ?>"><?= e($settings['phone']) ?></a>
                <?php endif; ?>
            </p>
        </div>
    </footer>

    <script src="<?= e(asset('js/menu.js')) ?>?v=2"></script>
</body>
</html>
