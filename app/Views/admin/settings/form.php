<?php
$name = old('restaurant_name', (string) ($settings['restaurant_name'] ?? ''));
$phone = old('phone', (string) ($settings['phone'] ?? ''));
$address = old('address', (string) ($settings['address'] ?? ''));
$hours = old('working_hours', (string) ($settings['working_hours'] ?? ''));
$about = old('about', (string) ($settings['about'] ?? ''));
$aboutRu = old('about_ru', (string) ($settings['about_ru'] ?? ''));
$aboutEn = old('about_en', (string) ($settings['about_en'] ?? ''));
$heritageStory = old('heritage_story', (string) ($settings['heritage_story'] ?? ''));
$heritageStoryRu = old('heritage_story_ru', (string) ($settings['heritage_story_ru'] ?? ''));
$heritageStoryEn = old('heritage_story_en', (string) ($settings['heritage_story_en'] ?? ''));
$tagline = old('tagline', (string) ($settings['tagline'] ?? ''));
$taglineRu = old('tagline_ru', (string) ($settings['tagline_ru'] ?? ''));
$taglineEn = old('tagline_en', (string) ($settings['tagline_en'] ?? ''));
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
        <div class="col-md-4">
            <label class="form-label" for="tagline">Tagline (AZ)</label>
            <input class="form-control" type="text" id="tagline" name="tagline" value="<?= e($tagline) ?>">
        </div>
        <div class="col-md-4">
            <label class="form-label" for="tagline_ru">Tagline (RU)</label>
            <input class="form-control" type="text" id="tagline_ru" name="tagline_ru" value="<?= e($taglineRu) ?>">
        </div>
        <div class="col-md-4">
            <label class="form-label" for="tagline_en">Tagline (EN)</label>
            <input class="form-control" type="text" id="tagline_en" name="tagline_en" value="<?= e($taglineEn) ?>">
        </div>
        <div class="col-md-4">
            <label class="form-label" for="about">Hero təsvir (AZ)</label>
            <textarea class="form-control" id="about" name="about" rows="2"><?= e($about) ?></textarea>
        </div>
        <div class="col-md-4">
            <label class="form-label" for="about_ru">Hero təsvir (RU)</label>
            <textarea class="form-control" id="about_ru" name="about_ru" rows="2"><?= e($aboutRu) ?></textarea>
        </div>
        <div class="col-md-4">
            <label class="form-label" for="about_en">Hero təsvir (EN)</label>
            <textarea class="form-control" id="about_en" name="about_en" rows="2"><?= e($aboutEn) ?></textarea>
        </div>
        <div class="col-md-4">
            <label class="form-label" for="heritage_story">Karvan hekayəsi (AZ)</label>
            <textarea class="form-control" id="heritage_story" name="heritage_story" rows="3"><?= e($heritageStory) ?></textarea>
        </div>
        <div class="col-md-4">
            <label class="form-label" for="heritage_story_ru">Karvan hekayəsi (RU)</label>
            <textarea class="form-control" id="heritage_story_ru" name="heritage_story_ru" rows="3"><?= e($heritageStoryRu) ?></textarea>
        </div>
        <div class="col-md-4">
            <label class="form-label" for="heritage_story_en">Karvan hekayəsi (EN)</label>
            <textarea class="form-control" id="heritage_story_en" name="heritage_story_en" rows="3"><?= e($heritageStoryEn) ?></textarea>
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

        <div class="col-12"><hr class="my-1"></div>
        <div class="col-12">
            <h3 class="h6 mb-0">Hero atmosfer videosu</h3>
            <p class="form-text mb-0">3–5 saniyəlik sakit interyer klipi (MP4/WEBM, səssiz oxunur). Boş buraxsanız, standart klip istifadə olunur.</p>
        </div>
        <div class="col-md-6">
            <label class="form-label" for="hero_video">Video</label>
            <input class="form-control" type="file" id="hero_video" name="hero_video" accept="video/mp4,video/webm,video/quicktime,.mp4,.webm,.mov">
            <div class="form-text">MP4, WEBM, MOV · max 12MB</div>
        </div>
        <?php if (!empty($settings['hero_video'])): ?>
            <div class="col-md-6">
                <video src="<?= e(uploaded_url($settings['hero_video']) ?? '') ?>" class="img-thumbnail w-100" style="max-height:140px" muted playsinline controls></video>
                <div class="form-check mt-2">
                    <input class="form-check-input" type="checkbox" id="remove_hero_video" name="remove_hero_video" value="1">
                    <label class="form-check-label" for="remove_hero_video">Xüsusi videonu sil (standarta qayıt)</label>
                </div>
            </div>
        <?php endif; ?>
        <div class="col-md-6">
            <label class="form-label" for="hero_poster">Poster şəkil</label>
            <input class="form-control" type="file" id="hero_poster" name="hero_poster" accept="image/jpeg,image/png,image/webp,image/gif">
            <div class="form-text">Video yüklənənə qədər / reduced-motion üçün · max 2MB</div>
        </div>
        <?php if (!empty($settings['hero_poster'])): ?>
            <div class="col-md-6">
                <img src="<?= e(uploaded_url($settings['hero_poster']) ?? '') ?>" alt="Poster" class="img-thumbnail" style="max-height:100px">
                <div class="form-check mt-2">
                    <input class="form-check-input" type="checkbox" id="remove_hero_poster" name="remove_hero_poster" value="1">
                    <label class="form-check-label" for="remove_hero_poster">Posteri sil</label>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <div class="mt-4">
        <button type="submit" class="btn btn-admin">Yadda saxla</button>
    </div>
</form>
<?php clear_old(); ?>
