<?php
$name = old('restaurant_name', (string) ($settings['restaurant_name'] ?? ''));
$phone = old('phone', (string) ($settings['phone'] ?? ''));
$address = old('address', (string) ($settings['address'] ?? ''));
$hours = old('working_hours', (string) ($settings['working_hours'] ?? ''));
$about = old('about', (string) ($settings['about'] ?? ''));
?>
<form method="post" action="<?= e(url('admin/settings')) ?>" enctype="multipart/form-data" class="admin-form bg-white border rounded-3 p-4">
    <?= csrf_field() ?>
    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label" for="restaurant_name">Restoran adı *</label>
            <input class="form-control" type="text" id="restaurant_name" name="restaurant_name" value="<?= e($name) ?>" required>
        </div>
        <div class="col-md-6">
            <label class="form-label" for="phone">Telefon</label>
            <input class="form-control" type="text" id="phone" name="phone" value="<?= e($phone) ?>">
        </div>
        <div class="col-12">
            <label class="form-label" for="address">Ünvan</label>
            <input class="form-control" type="text" id="address" name="address" value="<?= e($address) ?>">
        </div>
        <div class="col-md-6">
            <label class="form-label" for="working_hours">İş saatı</label>
            <input class="form-control" type="text" id="working_hours" name="working_hours" value="<?= e($hours) ?>">
        </div>
        <div class="col-12">
            <label class="form-label" for="about">Qısa təsvir</label>
            <textarea class="form-control" id="about" name="about" rows="3"><?= e($about) ?></textarea>
        </div>
        <div class="col-md-6">
            <label class="form-label" for="logo">Logo</label>
            <input class="form-control" type="file" id="logo" name="logo" accept="image/jpeg,image/png,image/webp,image/gif">
            <div class="form-text">JPEG, PNG, WEBP, GIF · max 2MB</div>
        </div>
        <?php if (!empty($settings['logo'])): ?>
            <div class="col-md-6">
                <img src="<?= e(upload_url($settings['logo'])) ?>" alt="Logo" class="img-thumbnail" style="max-height:100px">
                <div class="form-check mt-2">
                    <input class="form-check-input" type="checkbox" id="remove_logo" name="remove_logo" value="1">
                    <label class="form-check-label" for="remove_logo">Logonu sil</label>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <div class="mt-4">
        <button type="submit" class="btn btn-admin">Yadda saxla</button>
    </div>
</form>
<?php clear_old(); ?>
