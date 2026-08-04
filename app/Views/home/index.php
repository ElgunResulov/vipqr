<?php
$heroVideo = hero_video_url($settings);
$heroPoster = hero_poster_url($settings);
$hasHeroVideo = $heroVideo !== null;
?>
<section class="hero hero--heritage <?= $hasHeroVideo ? 'hero--video' : 'hero--still' ?>">
    <?php if ($hasHeroVideo): ?>
        <video class="hero-video"
               id="heroAmbiance"
               autoplay
               muted
               loop
               playsinline
               preload="metadata"
               poster="<?= e($heroPoster) ?>"
               aria-label="<?= e(__('hero.video_label')) ?>">
            <source src="<?= e($heroVideo) ?>" type="video/mp4">
        </video>
    <?php else: ?>
        <div class="hero-still" style="--hero-still: url('<?= e($heroPoster) ?>')" aria-hidden="true"></div>
    <?php endif; ?>
    <div class="hero-backdrop" aria-hidden="true"></div>
    <div class="hero-pattern" aria-hidden="true"></div>
    <div class="container hero-inner">
        <p class="hero-kicker"><?= e(__('hero.kicker')) ?></p>
        <h1 class="hero-brand"><?= e($settings['restaurant_name'] ?? 'VIP Karvan') ?></h1>
        <?php require dirname(__DIR__) . '/partials/ornament.php'; ?>
        <p class="hero-lead"><?= e(setting_localized($settings, 'about', 'hero.fallback_about')) ?></p>
        <a class="btn btn-hero" href="#menyü" data-soft-scroll><?= e(__('hero.cta')) ?></a>
    </div>
</section>

<section class="heritage-story">
    <div class="container heritage-story-inner">
        <?php require dirname(__DIR__) . '/partials/ornament.php'; ?>
        <h2 class="heritage-title"><?= e(__('heritage.title')) ?></h2>
        <p class="heritage-text"><?= e(setting_localized($settings, 'heritage_story', 'heritage.fallback')) ?></p>
        <?php require dirname(__DIR__) . '/partials/ornament.php'; ?>
    </div>
</section>

<?php if ($search === '' && !empty($featured)): ?>
<section class="featured-section" id="sef-tovsiyesi">
    <div class="container">
        <div class="featured-head">
            <?php require dirname(__DIR__) . '/partials/ornament.php'; ?>
            <h2><?= e(__('featured.title')) ?></h2>
            <p><?= e(__('featured.subtitle')) ?></p>
        </div>
        <div class="featured-list">
            <?php foreach ($featured as $product): ?>
                <?php
                $showCategoryLabel = true;
                $forceFeaturedBadge = true;
                $skipProductAnchor = true;
                require dirname(__DIR__) . '/menu/_product_card.php';
                ?>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if ($search === '' && !empty($popular)): ?>
<section class="popular-section" id="populyar">
    <div class="container">
        <div class="popular-head">
            <?php require dirname(__DIR__) . '/partials/ornament.php'; ?>
            <h2><?= e(__('popular.title')) ?></h2>
            <p><?= e(__('popular.subtitle')) ?></p>
        </div>
        <div class="popular-list">
            <?php
            $rank = 0;
            foreach ($popular as $product):
                $rank++;
                $showCategoryLabel = true;
                $forcePopularBadge = true;
                $popularRank = $rank;
                $skipProductAnchor = true;
                require dirname(__DIR__) . '/menu/_product_card.php';
            endforeach;
            ?>
        </div>
    </div>
</section>
<?php endif; ?>

<div class="filter-bar" id="menyü">
    <div class="container">
        <?php if (!empty($categories)): ?>
            <div class="category-rail" role="navigation" aria-label="<?= e(__('nav.menu')) ?>">
                <?php if ($search !== ''): ?>
                    <a class="cat-chip" href="<?= e(url()) ?>"><?= e(__('nav.all')) ?></a>
                    <?php foreach ($categories as $cat): ?>
                        <a class="cat-chip" href="<?= e(url('menu/' . $cat['slug'])) ?>"><?= e(localized($cat, 'name')) ?></a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <a class="cat-chip is-active" href="#menyü" data-soft-scroll><?= e(__('nav.all')) ?></a>
                    <?php foreach ($categories as $cat): ?>
                        <a class="cat-chip"
                           href="#cat-<?= e($cat['slug']) ?>"
                           data-soft-scroll><?= e(localized($cat, 'name')) ?></a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <form class="search-bar" method="get" action="<?= e(url()) ?>" role="search">
            <label class="visually-hidden" for="q"><?= e(__('search.button')) ?></label>
            <input type="search" id="q" name="q" class="form-control search-input" placeholder="<?= e(__('search.placeholder')) ?>" value="<?= e($search) ?>" autocomplete="off">
            <button type="submit" class="btn btn-search"><?= e(__('search.button')) ?></button>
        </form>
    </div>
</div>

<section class="container section-block">
    <?php if ($search !== ''): ?>
        <div class="section-head">
            <h2><?= e(__('search.results')) ?></h2>
            <p><?= e(__('search.count', ['count' => (string) count($products), 'q' => $search])) ?></p>
        </div>

        <?php if (empty($products)): ?>
            <div class="empty-state"><p><?= e(__('search.empty')) ?></p></div>
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
        <div class="empty-state"><p><?= e(__('empty.categories')) ?></p></div>
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
            $introKey = 'intro.' . $cat['slug'];
            $intro = __($introKey);
            if ($intro === $introKey) {
                $intro = null;
            }
            ?>
            <div class="menu-group" id="cat-<?= e($cat['slug']) ?>">
                <div class="menu-group-title">
                    <div class="menu-group-heading">
                        <?php require dirname(__DIR__) . '/partials/ornament.php'; ?>
                        <h2><?= e(localized($cat, 'name')) ?></h2>
                        <?php if ($intro !== null): ?>
                            <p class="menu-group-intro"><?= e($intro) ?></p>
                        <?php endif; ?>
                    </div>
                    <a href="<?= e(url('menu/' . $cat['slug'])) ?>"><?= e(__('nav.view_all')) ?></a>
                </div>
                <div class="product-list">
                    <?php foreach ($items as $product): ?>
                        <?php require dirname(__DIR__) . '/menu/_product_card.php'; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>

        <?php if (empty($products)): ?>
            <div class="empty-state"><p><?= e(__('empty.products')) ?></p></div>
        <?php endif; ?>
    <?php endif; ?>
</section>
