<div class="row g-3">
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-label">Kateqoriyalar</div>
            <div class="stat-value"><?= (int) $categoryCount ?></div>
            <a href="<?= e(url('admin/categories')) ?>">İdarə et →</a>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-label">Məhsullar</div>
            <div class="stat-value"><?= (int) $productCount ?></div>
            <a href="<?= e(url('admin/products')) ?>">İdarə et →</a>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-label">Restoran</div>
            <div class="stat-value text-truncate" style="font-size:1.35rem"><?= e($settings['restaurant_name'] ?? '—') ?></div>
            <a href="<?= e(url('admin/settings')) ?>">Parametrlər →</a>
        </div>
    </div>
</div>

<div class="mt-4 p-3 bg-white border rounded-3">
    <h2 class="h6">Tez keçidlər</h2>
    <div class="d-flex flex-wrap gap-2">
        <a class="btn btn-sm btn-admin" href="<?= e(url('admin/products/create')) ?>">Yeni məhsul</a>
        <a class="btn btn-sm btn-outline-secondary" href="<?= e(url('admin/categories/create')) ?>">Yeni kateqoriya</a>
        <a class="btn btn-sm btn-outline-secondary" href="<?= e(url('admin/qr')) ?>">QR kod</a>
    </div>
</div>
