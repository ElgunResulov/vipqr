<section class="page-banner">
    <div class="container">
        <a class="back-link" href="<?= e(url()) ?>">← Ana səhifə</a>
        <h1 class="page-title mt-2"><?= e($category['name']) ?></h1>
        <p class="page-sub"><?= e($settings['restaurant_name'] ?? 'VIP Karvan') ?></p>
    </div>
</section>

<div class="filter-bar">
    <div class="container">
        <div class="category-rail" role="navigation" aria-label="Kateqoriyalar">
            <a class="cat-chip" href="<?= e(url()) ?>">Hamısı</a>
            <?php foreach ($categories as $cat): ?>
                <a class="cat-chip <?= $cat['id'] === $category['id'] ? 'is-active' : '' ?>"
                   href="<?= e(url('menu/' . $cat['slug'])) ?>"><?= e($cat['name']) ?></a>
            <?php endforeach; ?>
        </div>

        <form class="search-bar" method="get" action="<?= e(url('menu/' . $category['slug'])) ?>" role="search">
            <label class="visually-hidden" for="q">Axtarış</label>
            <input type="search" id="q" name="q" class="form-control search-input" placeholder="Bu kateqoriyada axtar..." value="<?= e($search) ?>">
            <button type="submit" class="btn btn-search">Axtar</button>
        </form>
    </div>
</div>

<section class="container section-block">
    <?php if ($search !== ''): ?>
        <div class="section-head">
            <h2>Axtarış</h2>
            <p><?= count($products) ?> nəticə</p>
        </div>
    <?php endif; ?>

    <?php if (empty($products)): ?>
        <div class="empty-state">
            <p><?= $search !== '' ? 'Axtarışa uyğun məhsul tapılmadı.' : 'Bu kateqoriyada məhsul yoxdur.' ?></p>
        </div>
    <?php else: ?>
        <div class="product-list">
            <?php
            $showCategoryLabel = false;
            foreach ($products as $product):
                require __DIR__ . '/_product_card.php';
            endforeach;
            ?>
        </div>
    <?php endif; ?>
</section>
