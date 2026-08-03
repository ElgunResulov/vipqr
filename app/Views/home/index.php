<section class="hero">
    <div class="hero-backdrop" aria-hidden="true"></div>
    <div class="container hero-inner">
        <p class="hero-kicker">Şəki · Rəqəmsal menyü</p>
        <h1 class="hero-brand"><?= e($settings['restaurant_name'] ?? 'VIP Karvan') ?></h1>
        <hr class="hero-rule" aria-hidden="true">
        <p class="hero-lead"><?= e($settings['about'] ?? 'Dadlı yeməklərimizi kəşf edin.') ?></p>
        <a class="btn btn-hero" href="#menyü">Menyüyə bax</a>
    </div>
</section>

<div class="filter-bar" id="menyü">
    <div class="container">
        <?php if (!empty($categories)): ?>
            <div class="category-rail" role="navigation" aria-label="Kateqoriyalar">
                <a class="cat-chip <?= $search === '' ? 'is-active' : '' ?>" href="<?= e(url()) ?>">Hamısı</a>
                <?php foreach ($categories as $cat): ?>
                    <a class="cat-chip" href="<?= e(url('menu/' . $cat['slug'])) ?>"><?= e($cat['name']) ?></a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form class="search-bar" method="get" action="<?= e(url()) ?>" role="search">
            <label class="visually-hidden" for="q">Axtarış</label>
            <input type="search" id="q" name="q" class="form-control search-input" placeholder="Yemək axtarın..." value="<?= e($search) ?>" autocomplete="off">
            <button type="submit" class="btn btn-search">Axtar</button>
        </form>
    </div>
</div>

<section class="container section-block">
    <?php if ($search !== ''): ?>
        <div class="section-head">
            <h2>Axtarış nəticələri</h2>
            <p><?= count($products) ?> məhsul · «<?= e($search) ?>»</p>
        </div>

        <?php if (empty($products)): ?>
            <div class="empty-state"><p>Axtarışa uyğun məhsul tapılmadı.</p></div>
        <?php else: ?>
            <div class="product-list">
                <?php
                $showCategoryLabel = true;
                foreach ($products as $product):
                    require dirname(__DIR__) . '/menu/_product_card.php';
                endforeach;
                ?>
            </div>
        <?php endif; ?>
    <?php elseif (empty($categories)): ?>
        <div class="empty-state"><p>Hələ kateqoriya əlavə edilməyib.</p></div>
    <?php else: ?>
        <?php
        $grouped = [];
        foreach ($products as $product) {
            $cid = (int) $product['category_id'];
            $grouped[$cid][] = $product;
        }
        ?>
        <?php foreach ($categories as $cat): ?>
            <?php
            $cid = (int) $cat['id'];
            $items = $grouped[$cid] ?? [];
            if ($items === []) {
                continue;
            }
            ?>
            <div class="menu-group" id="cat-<?= e($cat['slug']) ?>">
                <div class="menu-group-title">
                    <h2><?= e($cat['name']) ?></h2>
                    <a href="<?= e(url('menu/' . $cat['slug'])) ?>">Hamısına bax</a>
                </div>
                <div class="product-list">
                    <?php foreach ($items as $product): ?>
                        <?php require dirname(__DIR__) . '/menu/_product_card.php'; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>

        <?php if (empty($products)): ?>
            <div class="empty-state"><p>Hələ məhsul əlavə edilməyib.</p></div>
        <?php endif; ?>
    <?php endif; ?>
</section>
