<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <form method="get" class="d-flex gap-2">
        <select name="category_id" class="form-select form-select-sm" onchange="this.form.submit()">
            <option value="">Bütün kateqoriyalar</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= (int) $cat['id'] ?>" <?= $selectedCategoryId === (int) $cat['id'] ? 'selected' : '' ?>>
                    <?= e($cat['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>
    <a class="btn btn-admin btn-sm" href="<?= e(url('admin/products/create')) ?>">+ Yeni məhsul</a>
</div>

<?php if (empty($products)): ?>
    <div class="empty-admin">Heç bir məhsul yoxdur.</div>
<?php else: ?>
    <div class="table-responsive bg-white border rounded-3">
        <table class="table table-hover mb-0 align-middle">
            <thead>
            <tr>
                <th>Məhsul</th>
                <th>Kateqoriya</th>
                <th>Qiymət</th>
                <th>Status</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($products as $product): ?>
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <img src="<?= e(upload_url($product['image'] ?? null)) ?>" alt="" width="48" height="48" class="rounded object-fit-cover">
                            <div>
                                <strong><?= e($product['name']) ?></strong>
                                <?php if (empty($product['is_available'])): ?>
                                    <div class="small text-warning">Mövcud deyil</div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </td>
                    <td><?= e($product['category_name']) ?></td>
                    <td><?= e(money_azn($product['price'])) ?></td>
                    <td>
                        <?php if ($product['is_active']): ?>
                            <span class="badge text-bg-success">Aktiv</span>
                        <?php else: ?>
                            <span class="badge text-bg-secondary">Deaktiv</span>
                        <?php endif; ?>
                    </td>
                    <td class="text-end text-nowrap">
                        <a class="btn btn-sm btn-outline-secondary" href="<?= e(url('admin/products/' . $product['id'] . '/edit')) ?>">Redaktə</a>
                        <form method="post" action="<?= e(url('admin/products/' . $product['id'] . '/delete')) ?>" class="d-inline" onsubmit="return confirm('Silinsin?');">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn btn-sm btn-outline-danger">Sil</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>
