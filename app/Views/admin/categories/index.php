<div class="d-flex justify-content-between align-items-center mb-3">
    <p class="mb-0 text-muted"><?= count($categories) ?> kateqoriya</p>
    <a class="btn btn-admin btn-sm" href="<?= e(url('admin/categories/create')) ?>">+ Yeni</a>
</div>

<?php if (empty($categories)): ?>
    <div class="empty-admin">Heç bir kateqoriya yoxdur.</div>
<?php else: ?>
    <div class="table-responsive bg-white border rounded-3">
        <table class="table table-hover mb-0 align-middle">
            <thead>
            <tr>
                <th>Ad</th>
                <th>Slug</th>
                <th>Sıra</th>
                <th>Məhsul</th>
                <th>Status</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($categories as $cat): ?>
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <?php if (!empty($cat['image'])): ?>
                                <img src="<?= e(upload_url($cat['image'])) ?>" alt="" width="40" height="40" class="rounded object-fit-cover">
                            <?php endif; ?>
                            <strong><?= e($cat['name']) ?></strong>
                        </div>
                    </td>
                    <td><code><?= e($cat['slug']) ?></code></td>
                    <td><?= (int) $cat['sort_order'] ?></td>
                    <td><?= (int) $cat['product_count'] ?></td>
                    <td>
                        <?php if ($cat['is_active']): ?>
                            <span class="badge text-bg-success">Aktiv</span>
                        <?php else: ?>
                            <span class="badge text-bg-secondary">Deaktiv</span>
                        <?php endif; ?>
                    </td>
                    <td class="text-end text-nowrap">
                        <a class="btn btn-sm btn-outline-secondary" href="<?= e(url('admin/categories/' . $cat['id'] . '/edit')) ?>">Redaktə</a>
                        <form method="post" action="<?= e(url('admin/categories/' . $cat['id'] . '/delete')) ?>" class="d-inline" onsubmit="return confirm('Silinsin?');">
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
