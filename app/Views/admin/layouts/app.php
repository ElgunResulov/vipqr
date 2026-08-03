<!DOCTYPE html>
<html lang="az">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex,nofollow">
    <title><?= e($title ?? 'Admin') ?> — VIP Karvan</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= e(asset('css/admin.css')) ?>" rel="stylesheet">
</head>
<body class="admin-body">
    <div class="admin-shell">
        <aside class="admin-sidebar">
            <div class="sidebar-brand">VIP Karvan</div>
            <?php
                $uriPath = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?: '';
                $isDashboard = (bool) preg_match('#/admin/?(\?|$)#', $uriPath);
                ?>
            <nav class="sidebar-nav">
                <a class="<?= $isDashboard ? 'active' : '' ?>" href="<?= e(url('admin')) ?>">Panel</a>
                <a class="<?= str_contains($uriPath, '/admin/categories') ? 'active' : '' ?>" href="<?= e(url('admin/categories')) ?>">Kateqoriyalar</a>
                <a class="<?= str_contains($uriPath, '/admin/products') ? 'active' : '' ?>" href="<?= e(url('admin/products')) ?>">Məhsullar</a>
                <a class="<?= str_contains($uriPath, '/admin/settings') ? 'active' : '' ?>" href="<?= e(url('admin/settings')) ?>">Parametrlər</a>
                <a class="<?= str_contains($uriPath, '/admin/qr') ? 'active' : '' ?>" href="<?= e(url('admin/qr')) ?>">QR kod</a>
                <a href="<?= e(url()) ?>" target="_blank" rel="noopener">Menyünü aç</a>
            </nav>
            <form method="post" action="<?= e(url('admin/logout')) ?>" class="sidebar-logout">
                <?= csrf_field() ?>
                <button type="submit" class="btn btn-sm btn-outline-light w-100">Çıxış</button>
            </form>
        </aside>
        <div class="admin-main">
            <header class="admin-header">
                <h1 class="h4 mb-0"><?= e($title ?? '') ?></h1>
                <span class="text-muted small"><?= e(\App\Core\Auth::username() ?? '') ?></span>
            </header>
            <div class="admin-content">
                <?php if ($msg = flash('success')): ?>
                    <div class="alert alert-success"><?= e($msg) ?></div>
                <?php endif; ?>
                <?php if ($msg = flash('error')): ?>
                    <div class="alert alert-danger"><?= e($msg) ?></div>
                <?php endif; ?>
                <?= $content ?>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
