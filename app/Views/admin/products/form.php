<?php
$isEdit = $product !== null;
$action = $isEdit ? url('admin/products/' . $product['id']) : url('admin/products');
$name = old('name', $isEdit ? (string) $product['name'] : '');
$description = old('description', $isEdit ? (string) ($product['description'] ?? '') : '');
$categoryId = old('category_id', $isEdit ? (string) $product['category_id'] : '');
$price = old('price', $isEdit ? (string) $product['price'] : '');
$sort = old('sort_order', $isEdit ? (string) $product['sort_order'] : '0');
$active = old('is_active', $isEdit ? (string) $product['is_active'] : '1') === '1';
$available = old('is_available', $isEdit ? (string) $product['is_available'] : '1') === '1';
?>
<form method="post" action="<?= e($action) ?>" enctype="multipart/form-data" class="admin-form bg-white border rounded-3 p-4">
    <?= csrf_field() ?>
    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label" for="name">Ad *</label>
            <input class="form-control" type="text" id="name" name="name" value="<?= e($name) ?>" required>
        </div>
        <div class="col-md-6">
            <label class="form-label" for="category_id">Kateqoriya *</label>
            <select class="form-select" id="category_id" name="category_id" required>
                <option value="">Seçin</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= (int) $cat['id'] ?>" <?= (string) $cat['id'] === $categoryId ? 'selected' : '' ?>>
                        <?= e($cat['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-12">
            <label class="form-label" for="description">Təsvir</label>
            <textarea class="form-control" id="description" name="description" rows="3"><?= e($description) ?></textarea>
        </div>
        <div class="col-md-4">
            <label class="form-label" for="price">Qiymət (AZN) *</label>
            <input class="form-control" type="text" id="price" name="price" value="<?= e($price) ?>" required>
        </div>
        <div class="col-md-4">
            <label class="form-label" for="sort_order">Sıra</label>
            <input class="form-control" type="number" id="sort_order" name="sort_order" value="<?= e($sort) ?>">
        </div>
        <div class="col-md-4 d-flex align-items-end gap-3">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" <?= $active ? 'checked' : '' ?>>
                <label class="form-check-label" for="is_active">Aktiv</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="is_available" name="is_available" value="1" <?= $available ? 'checked' : '' ?>>
                <label class="form-check-label" for="is_available">Mövcuddur</label>
            </div>
        </div>
        <div class="col-md-6">
            <label class="form-label" for="image">Şəkil</label>
            <input class="form-control" type="file" id="image" name="image" accept="image/jpeg,image/png,image/webp,image/gif">
            <div class="form-text">JPEG, PNG, WEBP, GIF · max 2MB</div>
        </div>
        <?php if ($isEdit && !empty($product['image'])): ?>
            <div class="col-md-6">
                <img src="<?= e(upload_url($product['image'])) ?>" alt="" class="img-thumbnail" style="max-height:120px">
                <div class="form-check mt-2">
                    <input class="form-check-input" type="checkbox" id="remove_image" name="remove_image" value="1">
                    <label class="form-check-label" for="remove_image">Şəkli sil</label>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <div class="mt-4 d-flex gap-2">
        <button type="submit" class="btn btn-admin">Yadda saxla</button>
        <a class="btn btn-outline-secondary" href="<?= e(url('admin/products')) ?>">Ləğv et</a>
    </div>
</form>
<?php clear_old(); ?>
