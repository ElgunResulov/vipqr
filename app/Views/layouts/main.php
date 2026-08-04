<?php
$settings = $settings ?? [];
$currentLocale = locale();
$htmlLang = $currentLocale === 'az' ? 'az' : $currentLocale;
?>
<!DOCTYPE html>
<html lang="<?= e($htmlLang) ?>" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?= e(($settings['restaurant_name'] ?? 'VIP Karvan') . ' ' . __('meta.menu_suffix')) ?>">
    <meta name="color-scheme" content="light dark">
    <title><?= e($title ?? 'VIP Karvan') ?></title>
    <script>
      (function () {
        try {
          var key = 'vipqr-theme';
          var saved = localStorage.getItem(key);
          var theme = (saved === 'dark' || saved === 'light')
            ? saved
            : (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
          document.documentElement.setAttribute('data-theme', theme);
        } catch (e) {
          document.documentElement.setAttribute('data-theme', 'light');
        }
      })();
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700&family=Figtree:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= e(asset('css/menu.css')) ?>?v=12" rel="stylesheet">
</head>
<body class="menu-body"
      data-views-url="<?= e(url('api/views')) ?>"
      data-fav-url="<?= e(url('api/favorites')) ?>"
      data-i18n-fav-add="<?= e(__('fav.add')) ?>"
      data-i18n-fav-remove="<?= e(__('fav.remove')) ?>"
      data-i18n-share-product="<?= e(__('share.product')) ?>"
      data-i18n-share-copied="<?= e(__('share.copied')) ?>"
      data-i18n-share-list="<?= e(__('share.list_copied')) ?>"
      data-i18n-fav-empty="<?= e(__('fav.empty')) ?>"
      data-restaurant-name="<?= e($settings['restaurant_name'] ?? 'VIP Karvan') ?>">
    <header class="menu-topbar" id="menuTopbar">
        <div class="container topbar-inner">
            <a class="brand-link" href="<?= e(url()) ?>">
                <?php if (!empty($settings['logo'])): ?>
                    <img src="<?= e(upload_url($settings['logo'])) ?>" alt="<?= e($settings['restaurant_name'] ?? 'VIP Karvan') ?>" class="brand-logo">
                <?php else: ?>
                    <span class="brand-name"><?= e($settings['restaurant_name'] ?? 'VIP Karvan') ?></span>
                <?php endif; ?>
            </a>
            <div class="topbar-actions">
                <button type="button"
                        class="fav-toggle"
                        id="favToggle"
                        aria-expanded="false"
                        aria-controls="favPanel"
                        aria-label="<?= e(__('fav.title')) ?>"
                        title="<?= e(__('fav.title')) ?>">
                    <svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 3.6l2.4 4.86 5.36.78-3.88 3.78.92 5.34L12 15.9l-4.8 2.52.92-5.34-3.88-3.78 5.36-.78L12 3.6z"/>
                    </svg>
                    <span class="fav-count" id="favCount" hidden>0</span>
                </button>
                <div class="lang-switch" role="navigation" aria-label="<?= e(__('lang.label')) ?>">
                    <?php foreach (\App\Core\Lang::labels() as $code => $label): ?>
                        <a class="lang-btn <?= $currentLocale === $code ? 'is-active' : '' ?>"
                           href="<?= e(lang_url($code)) ?>"
                           hreflang="<?= e($code) ?>"
                           lang="<?= e($code) ?>"><?= e($label) ?></a>
                    <?php endforeach; ?>
                </div>
                <button type="button"
                        class="theme-toggle"
                        id="themeToggle"
                        data-label-dark="<?= e(__('theme.to_dark')) ?>"
                        data-label-light="<?= e(__('theme.to_light')) ?>"
                        aria-label="<?= e(__('theme.label')) ?>"
                        title="<?= e(__('theme.label')) ?>">
                    <svg class="icon-moon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M21 14.3A8.5 8.5 0 0 1 9.7 3a7 7 0 1 0 11.3 11.3z"/>
                    </svg>
                    <svg class="icon-sun" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="12" cy="12" r="4"/>
                        <path d="M12 2v2M12 20v2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M2 12h2M20 12h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"/>
                    </svg>
                </button>
                <?php if (!empty($settings['phone'])): ?>
                    <a class="topbar-phone" href="tel:<?= e(preg_replace('/\s+/', '', $settings['phone'])) ?>">
                        <?= e($settings['phone']) ?>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <div class="fav-panel" id="favPanel" hidden>
        <div class="fav-panel-inner">
            <div class="fav-panel-head">
                <h2><?= e(__('fav.title')) ?></h2>
                <button type="button" class="fav-panel-close" id="favClose" aria-label="<?= e(__('fav.close')) ?>">×</button>
            </div>
            <p class="fav-panel-lead"><?= e(__('fav.subtitle')) ?></p>
            <ul class="fav-list" id="favList"></ul>
            <p class="fav-empty" id="favEmpty" hidden><?= e(__('fav.empty')) ?></p>
            <div class="fav-panel-actions">
                <button type="button" class="btn btn-search" id="favShareList"><?= e(__('share.list')) ?></button>
                <button type="button" class="btn btn-outline-secondary fav-clear" id="favClear"><?= e(__('fav.clear')) ?></button>
            </div>
        </div>
    </div>
    <div class="fav-backdrop" id="favBackdrop" hidden></div>
    <div class="menu-toast" id="menuToast" role="status" aria-live="polite" hidden></div>

    <main>
        <?= $content ?>
    </main>

    <footer class="menu-footer">
        <div class="container footer-inner">
            <?php require dirname(__DIR__) . '/partials/ornament.php'; ?>
            <p class="footer-brand"><?= e($settings['restaurant_name'] ?? 'VIP Karvan') ?></p>
            <p class="footer-tagline"><?= e(setting_localized($settings, 'tagline', 'tagline.fallback')) ?></p>
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

    <script src="<?= e(asset('js/menu.js')) ?>?v=9"></script>
</body>
</html>
