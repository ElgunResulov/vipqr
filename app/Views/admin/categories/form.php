<?php
$isEdit = $category !== null;
$action = $isEdit ? url('admin/categories/' . $category['id']) : url('admin/categories');
$name = old('name', $isEdit ? (string) $category['name'] : '');
$nameRu = old('name_ru', $isEdit ? (string) ($category['name_ru'] ?? '') : '');
$nameEn = old('name_en', $isEdit ? (string) ($category['name_en'] ?? '') : '');
$slug = old('slug', $isEdit ? (string) $category['slug'] : '');
$sort = old('sort_order', $isEdit ? (string) $category['sort_order'] : '0');
$active = old('is_active', $isEdit ? (string) $category['is_active'] : '1') === '1';
?>
<form method="post" action="<?= e($action) ?>" enctype="multipart/form-data" class="admin-form bg-white border rounded-3 p-4">
    <?= csrf_field() ?>
    <div class="row g-3">
        <div class="col-md-4">
            <label class="form-label" for="name">Ad (AZ) *</label>
            <input class="form-control" type="text" id="name" name="name" value="<?= e($name) ?>" required>
        </div>
        <div class="col-md-4">
            <label class="form-label" for="name_ru">Ad (RU)</label>
            <input class="form-control" type="text" id="name_ru" name="name_ru" value="<?= e($nameRu) ?>">
        </div>
        <div class="col-md-4">
            <label class="form-label" for="name_en">Ad (EN)</label>
            <input class="form-control" type="text" id="name_en" name="name_en" value="<?= e($nameEn) ?>">
        </div>
        <div class="col-md-6">
            <label class="form-label" for="slug">Slug</label>
            <input class="form-control" type="text" id="slug" name="slug" value="<?= e($slug) ?>" placeholder="Avtomatik">
        </div>
        <div class="col-md-3">
            <label class="form-label" for="sort_order">Sıra</label>
            <input class="form-control" type="number" id="sort_order" name="sort_order" value="<?= e($sort) ?>">
        </div>
        <div class="col-md-3 d-flex align-items-end">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" <?= $active ? 'checked' : '' ?>>
                <label class="form-check-label" for="is_active">Aktiv</label>
            </div>
        </div>
        <div class="col-md-6">
            <label class="form-label" for="image">Şəkil</label>
            <input class="form-control" type="file" id="image" name="image" accept="image/jpeg,image/png,image/webp,image/gif">
            <div class="form-text">JPEG, PNG, WEBP, GIF · max 2MB</div>
        </div>
        <?php if ($isEdit && !empty($category['image'])): ?>
            <div class="col-md-6">
                <img src="<?= e(upload_url($category['image'])) ?>" alt="" class="img-thumbnail" style="max-height:120px">
                <div class="form-check mt-2">
                    <input class="form-check-input" type="checkbox" id="remove_image" name="remove_image" value="1">
                    <label class="form-check-label" for="remove_image">Şəkli sil</label>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <div class="mt-4 d-flex gap-2">
        <button type="submit" class="btn btn-admin">Yadda saxla</button>
        <a class="btn btn-outline-secondary" href="<?= e(url('admin/categories')) ?>">Ləğv et</a>
    </div>
</form>
<?php clear_old(); ?>
