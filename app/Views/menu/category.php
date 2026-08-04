<?php
$introKey = 'intro.' . $category['slug'];
$intro = __($introKey);
if ($intro === $introKey) {
    $intro = null;
}
?>
<section class="page-banner page-banner--heritage">
    <div class="container">
        <a class="back-link" href="<?= e(url()) ?>"><?= e(__('nav.back_home')) ?></a>
        <?php require dirname(__DIR__) . '/partials/ornament.php'; ?>
        <h1 class="page-title mt-2"><?= e(localized($category, 'name')) ?></h1>
        <?php if ($intro !== null): ?>
            <p class="page-sub page-sub--intro"><?= e($intro) ?></p>
        <?php endif; ?>
        <p class="page-sub"><?= e($settings['restaurant_name'] ?? 'VIP Karvan') ?></p>
    </div>
</section>

<div class="filter-bar">
    <div class="container">
        <div class="category-rail" role="navigation" aria-label="<?= e(__('nav.menu')) ?>">
            <a class="cat-chip" href="<?= e(url()) ?>"><?= e(__('nav.all')) ?></a>
            <?php foreach ($categories as $cat): ?>
                <a class="cat-chip <?= $cat['id'] === $category['id'] ? 'is-active' : '' ?>"
                   href="<?= e(url('menu/' . $cat['slug'])) ?>"><?= e(localized($cat, 'name')) ?></a>
            <?php endforeach; ?>
        </div>

        <form class="search-bar" method="get" action="<?= e(url('menu/' . $category['slug'])) ?>" role="search">
            <label class="visually-hidden" for="q"><?= e(__('search.button')) ?></label>
            <input type="search" id="q" name="q" class="form-control search-input" placeholder="<?= e(__('search.placeholder_category')) ?>" value="<?= e($search) ?>">
            <button type="submit" class="btn btn-search"><?= e(__('search.button')) ?></button>
        </form>
    </div>
</div>

<section class="container section-block">
    <?php if ($search !== ''): ?>
        <div class="section-head">
            <h2><?= e(__('search.results')) ?></h2>
            <p><?= e(__('search.count_short', ['count' => (string) count($products)])) ?></p>
        </div>
    <?php endif; ?>

    <?php if (empty($products)): ?>
        <div class="empty-state">
            <p><?= e($search !== '' ? __('search.empty') : __('empty.category_products')) ?></p>
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
